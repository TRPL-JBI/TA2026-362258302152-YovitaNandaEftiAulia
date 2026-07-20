<?php

namespace App\Http\Controllers;

use App\Models\PeriodeAmi;
use App\Models\StandarMutu;
use App\Models\UnitKerja;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    /**
     * Dashboard Administrator.
     */
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | STATISTIK UTAMA
        |--------------------------------------------------------------------------
        */

        $totalStandar = $this->safeModelCount(
            StandarMutu::class
        );

        $totalPeriode = $this->safeModelCount(
            PeriodeAmi::class
        );

        $totalUnitKerja = $this->safeModelCount(
            UnitKerja::class
        );

        $totalPengguna = Schema::hasTable('users')
            ? DB::table('users')->count()
            : 0;

        /*
        |--------------------------------------------------------------------------
        | PERIODE AMI AKTIF
        |--------------------------------------------------------------------------
        */

        $periodeAktif = 0;

        if (
            Schema::hasTable('periode_ami')
            && Schema::hasColumn('periode_ami', 'status')
        ) {
            $periodeAktif = DB::table('periode_ami')
                ->whereRaw(
                    "LOWER(TRIM(COALESCE(status, ''))) = ?",
                    ['berjalan']
                )
                ->count();
        }

        /*
        |--------------------------------------------------------------------------
        | PENERAPAN STANDAR
        |--------------------------------------------------------------------------
        */

        $totalPenerapan = Schema::hasTable(
            'penerapan_standar'
        )
            ? DB::table('penerapan_standar')->count()
            : 0;

        /*
        |--------------------------------------------------------------------------
        | TEMUAN AMI
        |--------------------------------------------------------------------------
        */

        $totalTemuan = Schema::hasTable('temuan_ami')
            ? DB::table('temuan_ami')->count()
            : 0;

        $temuanOpen = 0;
        $temuanClosed = 0;

        if (
            Schema::hasTable('temuan_ami')
            && Schema::hasColumn(
                'temuan_ami',
                'status_temuan'
            )
        ) {
            $temuanOpen = DB::table('temuan_ami')
                ->whereRaw(
                    "
                    LOWER(
                        TRIM(
                            COALESCE(status_temuan, '')
                        )
                    ) = ?
                    ",
                    ['open']
                )
                ->count();

            $temuanClosed = DB::table('temuan_ami')
                ->whereRaw(
                    "
                    LOWER(
                        TRIM(
                            COALESCE(status_temuan, '')
                        )
                    ) = ?
                    ",
                    ['closed']
                )
                ->count();
        }

        /*
        |--------------------------------------------------------------------------
        | DAFTAR PERIODE AMI BERJALAN
        |--------------------------------------------------------------------------
        */

        $periodeBerjalan = collect();

        try {
            $periodeBerjalan = PeriodeAmi::with([
                'standarMutu',
                'unitKerja',
            ])
                ->whereRaw(
                    "
                    LOWER(
                        TRIM(
                            COALESCE(status, '')
                        )
                    ) = ?
                    ",
                    ['berjalan']
                )
                ->orderByDesc('tahun')
                ->orderByDesc('id')
                ->get();
        } catch (\Throwable $exception) {
            $periodeBerjalan = collect();
        }

        /*
        |--------------------------------------------------------------------------
        | RINGKASAN UNIT KERJA
        |--------------------------------------------------------------------------
        */

        $ringkasanUnit = $this->getRingkasanUnit();

        /*
        |--------------------------------------------------------------------------
        | NAMA ALTERNATIF UNTUK KOMPATIBILITAS VIEW LAMA
        |--------------------------------------------------------------------------
        */

        $rekapUnit = $ringkasanUnit;

        /*
        |--------------------------------------------------------------------------
        | AKTIVITAS PENERAPAN TERBARU
        |--------------------------------------------------------------------------
        */

        $aktivitasTerbaru = $this->getAktivitasTerbaru();

        /*
        |--------------------------------------------------------------------------
        | TAMPILKAN VIEW ADMIN
        |--------------------------------------------------------------------------
        */

        return view(
            'dashboard',
            compact(
                'totalStandar',
                'totalPeriode',
                'periodeAktif',
                'totalUnitKerja',
                'totalPengguna',
                'totalPenerapan',
                'totalTemuan',
                'temuanOpen',
                'temuanClosed',
                'periodeBerjalan',
                'ringkasanUnit',
                'rekapUnit',
                'aktivitasTerbaru'
            )
        );
    }

    /**
     * Route lama dashboard Auditee.
     *
     * Method ini dipertahankan sementara agar route lama tidak error.
     * Data dashboard Auditee tetap dikelola oleh controller khusus.
     */
    public function dashboardAuditee()
    {
        return app(
            DashboardAuditeeController::class
        )->index();
    }

    /**
     * Mengambil ringkasan audit untuk setiap unit kerja.
     */
    private function getRingkasanUnit()
    {
        if (!Schema::hasTable('unit_kerja')) {
            return collect();
        }

        /*
         * Apabila struktur tabel relasi belum lengkap,
         * tetap tampilkan daftar unit dengan nilai nol.
         */
        if (
            !Schema::hasTable('periode_ami')
            || !Schema::hasColumn(
                'periode_ami',
                'id_unit_kerja'
            )
        ) {
            return DB::table('unit_kerja')
                ->select([
                    'id',
                    DB::raw(
                        $this->unitNameExpression()
                        . ' AS nama_unit'
                    ),
                    DB::raw('0 AS jumlah_periode'),
                    DB::raw('0 AS jumlah_penerapan'),
                    DB::raw('0 AS temuan_open'),
                    DB::raw('0 AS temuan_closed'),
                ])
                ->orderBy('id')
                ->get();
        }

        $query = DB::table('unit_kerja as uk')
            ->leftJoin(
                'periode_ami as pa',
                'pa.id_unit_kerja',
                '=',
                'uk.id'
            );

        /*
         * Relasi penerapan dapat berbeda antarversi proyek.
         * Ringkasan dasar periode tetap ditampilkan dengan aman.
         */
        $query->select([
            'uk.id',

            DB::raw(
                $this->unitNameExpression('uk')
                . ' AS nama_unit'
            ),

            DB::raw(
                'COUNT(DISTINCT pa.id) AS jumlah_periode'
            ),

            DB::raw('0 AS jumlah_penerapan'),
            DB::raw('0 AS temuan_open'),
            DB::raw('0 AS temuan_closed'),
        ]);

        $groupColumns = ['uk.id'];

        if (
            Schema::hasColumn(
                'unit_kerja',
                'nama'
            )
        ) {
            $groupColumns[] = 'uk.nama';
        }

        if (
            Schema::hasColumn(
                'unit_kerja',
                'nama_unit_kerja'
            )
        ) {
            $groupColumns[] = 'uk.nama_unit_kerja';
        }

        return $query
            ->groupBy(...$groupColumns)
            ->orderBy('uk.id')
            ->get();
    }

    /**
     * Mengambil penerapan terbaru.
     */
    private function getAktivitasTerbaru()
    {
        if (!Schema::hasTable('penerapan_standar')) {
            return collect();
        }

        $query = DB::table(
            'penerapan_standar as ps'
        );

        if (
            Schema::hasTable('users')
            && Schema::hasColumn(
                'penerapan_standar',
                'id_user'
            )
        ) {
            $query->leftJoin(
                'users as u',
                'u.id',
                '=',
                'ps.id_user'
            );
        }

        $select = [
            'ps.id',
        ];

        if (
            Schema::hasColumn(
                'penerapan_standar',
                'deskripsi_hasil'
            )
        ) {
            $select[] = 'ps.deskripsi_hasil';
        } else {
            $select[] = DB::raw(
                "'' AS deskripsi_hasil"
            );
        }

        if (
            Schema::hasTable('users')
            && Schema::hasColumn('users', 'nama')
            && Schema::hasColumn(
                'penerapan_standar',
                'id_user'
            )
        ) {
            $select[] = 'u.nama as nama_user';
        } else {
            $select[] = DB::raw(
                "'Pengguna' AS nama_user"
            );
        }

        return $query
            ->select($select)
            ->orderByDesc('ps.id')
            ->limit(8)
            ->get();
    }

    /**
     * Menghasilkan ekspresi nama unit sesuai kolom yang tersedia.
     */
    private function unitNameExpression(
        string $alias = ''
    ): string {
        $prefix = $alias !== ''
            ? $alias . '.'
            : '';

        $hasNama = Schema::hasColumn(
            'unit_kerja',
            'nama'
        );

        $hasNamaUnit = Schema::hasColumn(
            'unit_kerja',
            'nama_unit_kerja'
        );

        if ($hasNama && $hasNamaUnit) {
            return "COALESCE(
                {$prefix}nama,
                {$prefix}nama_unit_kerja,
                '-'
            )";
        }

        if ($hasNama) {
            return "COALESCE(
                {$prefix}nama,
                '-'
            )";
        }

        if ($hasNamaUnit) {
            return "COALESCE(
                {$prefix}nama_unit_kerja,
                '-'
            )";
        }

        return "'Unit Kerja'";
    }

    /**
     * Menghitung model dengan aman.
     */
    private function safeModelCount(
        string $modelClass
    ): int {
        try {
            return (int) $modelClass::count();
        } catch (\Throwable $exception) {
            return 0;
        }
    }
}