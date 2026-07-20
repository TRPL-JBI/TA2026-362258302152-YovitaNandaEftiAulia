<?php

namespace App\Http\Controllers;

use App\Models\PeriodeAmi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardAuditorController extends Controller
{
    /**
     * Menampilkan dashboard Auditor.
     */
    public function index()
    {
        $idAuditor = $this->getLoginUserId();

        /*
        |--------------------------------------------------------------------------
        | PERIODE YANG DITUGASKAN
        |--------------------------------------------------------------------------
        */

        $idPeriodeDitugaskan = collect();

        if (
            Schema::hasTable('tim_ami')
            && Schema::hasColumn('tim_ami', 'id_user')
            && Schema::hasColumn('tim_ami', 'id_periode_ami')
        ) {
            $idPeriodeDitugaskan = DB::table('tim_ami')
                ->where('id_user', $idAuditor)
                ->pluck('id_periode_ami')
                ->filter()
                ->unique()
                ->values();
        }

        $jumlahPeriodeDitugaskan =
            $idPeriodeDitugaskan->count();

        /*
        |--------------------------------------------------------------------------
        | PERIODE AKTIF YANG DITUGASKAN
        |--------------------------------------------------------------------------
        */

        $periodeAktif = 0;
        $periodeBerjalan = collect();

        if (
            $idPeriodeDitugaskan->isNotEmpty()
            && Schema::hasTable('periode_ami')
        ) {
            $queryPeriodeAktif = DB::table('periode_ami')
                ->whereIn('id', $idPeriodeDitugaskan);

            if (
                Schema::hasColumn(
                    'periode_ami',
                    'status'
                )
            ) {
                $queryPeriodeAktif->whereRaw(
                    "
                    LOWER(
                        TRIM(
                            COALESCE(status, '')
                        )
                    ) = ?
                    ",
                    ['berjalan']
                );
            }

            $periodeAktif = $queryPeriodeAktif->count();

            try {
                $periodeBerjalan = PeriodeAmi::with([
                    'standarMutu',
                    'unitKerja',
                ])
                    ->whereIn(
                        'id',
                        $idPeriodeDitugaskan
                    )
                    ->when(
                        Schema::hasColumn(
                            'periode_ami',
                            'status'
                        ),
                        function ($query) {
                            $query->whereRaw(
                                "
                                LOWER(
                                    TRIM(
                                        COALESCE(status, '')
                                    )
                                ) = ?
                                ",
                                ['berjalan']
                            );
                        }
                    )
                    ->orderByDesc('tahun')
                    ->orderByDesc('id')
                    ->get();
            } catch (\Throwable $exception) {
                $periodeBerjalan = collect();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | JUMLAH PENERAPAN
        |--------------------------------------------------------------------------
        */

        $jumlahPenerapan = $this->countPenerapan(
            $idPeriodeDitugaskan
        );

        /*
        |--------------------------------------------------------------------------
        | JUMLAH TEMUAN OPEN DAN CLOSED
        |--------------------------------------------------------------------------
        */

        $temuanOpen = $this->countTemuan(
            $idPeriodeDitugaskan,
            'open'
        );

        $temuanClosed = $this->countTemuan(
            $idPeriodeDitugaskan,
            'closed'
        );

        $jumlahTemuan = $temuanOpen + $temuanClosed;

        /*
        |--------------------------------------------------------------------------
        | DAFTAR PENERAPAN YANG SIAP DIPERIKSA
        |--------------------------------------------------------------------------
        */

        $penerapanSiapDiperiksa =
            $this->getPenerapanSiapDiperiksa(
                $idPeriodeDitugaskan
            );

        /*
        |--------------------------------------------------------------------------
        | TEMUAN TERBARU
        |--------------------------------------------------------------------------
        */

        $temuanTerbaru =
            $this->getTemuanTerbaru(
                $idPeriodeDitugaskan
            );

        /*
        |--------------------------------------------------------------------------
        | NAMA VARIABEL ALTERNATIF
        |--------------------------------------------------------------------------
        |
        | Disediakan agar kompatibel dengan view dashboard Auditor
        | yang mungkin menggunakan nama variabel versi sebelumnya.
        |
        */

        $periodeDitugaskan =
            $jumlahPeriodeDitugaskan;

        $penerapanTersedia =
            $jumlahPenerapan;

        $temuanTerbukaTerbaru =
            $temuanTerbaru;

        return view(
            'auditor.dashboard',
            compact(
                'jumlahPeriodeDitugaskan',
                'periodeDitugaskan',
                'periodeAktif',
                'periodeBerjalan',
                'jumlahPenerapan',
                'penerapanTersedia',
                'jumlahTemuan',
                'temuanOpen',
                'temuanClosed',
                'penerapanSiapDiperiksa',
                'temuanTerbaru',
                'temuanTerbukaTerbaru'
            )
        );
    }

    /**
     * Menghitung penerapan pada periode yang ditugaskan.
     */
    private function countPenerapan(
        $idPeriodeDitugaskan
    ): int {
        if (
            $idPeriodeDitugaskan->isEmpty()
            || !Schema::hasTable('penerapan_standar')
            || !Schema::hasTable(
                'standarmutu_periodeami'
            )
            || !Schema::hasColumn(
                'penerapan_standar',
                'id_standarmutu_periodeami'
            )
            || !Schema::hasColumn(
                'standarmutu_periodeami',
                'id_periode_ami'
            )
        ) {
            return 0;
        }

        return DB::table('penerapan_standar as ps')
            ->join(
                'standarmutu_periodeami as smp',
                'smp.id',
                '=',
                'ps.id_standarmutu_periodeami'
            )
            ->whereIn(
                'smp.id_periode_ami',
                $idPeriodeDitugaskan
            )
            ->distinct()
            ->count('ps.id');
    }

    /**
     * Menghitung temuan berdasarkan status.
     */
    private function countTemuan(
        $idPeriodeDitugaskan,
        string $status
    ): int {
        if (
            $idPeriodeDitugaskan->isEmpty()
            || !Schema::hasTable('temuan_ami')
            || !Schema::hasTable('penerapan_standar')
            || !Schema::hasTable(
                'standarmutu_periodeami'
            )
            || !Schema::hasColumn(
                'temuan_ami',
                'id_penerapan_standar'
            )
            || !Schema::hasColumn(
                'temuan_ami',
                'status_temuan'
            )
            || !Schema::hasColumn(
                'penerapan_standar',
                'id_standarmutu_periodeami'
            )
            || !Schema::hasColumn(
                'standarmutu_periodeami',
                'id_periode_ami'
            )
        ) {
            return 0;
        }

        return DB::table('temuan_ami as ta')
            ->join(
                'penerapan_standar as ps',
                'ps.id',
                '=',
                'ta.id_penerapan_standar'
            )
            ->join(
                'standarmutu_periodeami as smp',
                'smp.id',
                '=',
                'ps.id_standarmutu_periodeami'
            )
            ->whereIn(
                'smp.id_periode_ami',
                $idPeriodeDitugaskan
            )
            ->whereRaw(
                "
                LOWER(
                    TRIM(
                        COALESCE(
                            ta.status_temuan,
                            ''
                        )
                    )
                ) = ?
                ",
                [$status]
            )
            ->distinct()
            ->count('ta.id');
    }

    /**
     * Mengambil penerapan yang siap diperiksa.
     */
    private function getPenerapanSiapDiperiksa(
        $idPeriodeDitugaskan
    ) {
        if (
            $idPeriodeDitugaskan->isEmpty()
            || !Schema::hasTable('penerapan_standar')
            || !Schema::hasTable(
                'standarmutu_periodeami'
            )
            || !Schema::hasColumn(
                'penerapan_standar',
                'id_standarmutu_periodeami'
            )
            || !Schema::hasColumn(
                'standarmutu_periodeami',
                'id_periode_ami'
            )
        ) {
            return collect();
        }

        $query = DB::table(
            'penerapan_standar as ps'
        )
            ->join(
                'standarmutu_periodeami as smp',
                'smp.id',
                '=',
                'ps.id_standarmutu_periodeami'
            )
            ->whereIn(
                'smp.id_periode_ami',
                $idPeriodeDitugaskan
            );

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
            Schema::hasColumn(
                'penerapan_standar',
                'link_bukti'
            )
        ) {
            $select[] = 'ps.link_bukti';
        } else {
            $select[] = DB::raw(
                "NULL AS link_bukti"
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DATA INDIKATOR
        |--------------------------------------------------------------------------
        */

        if (
            Schema::hasTable('indikator_standar')
            && Schema::hasColumn(
                'penerapan_standar',
                'id_indikator'
            )
        ) {
            $query->leftJoin(
                'indikator_standar as indikator',
                'indikator.id',
                '=',
                'ps.id_indikator'
            );

            if (
                Schema::hasColumn(
                    'indikator_standar',
                    'deskripsi'
                )
            ) {
                $select[] =
                    'indikator.deskripsi as indikator';
            } else {
                $select[] = DB::raw(
                    "'-' AS indikator"
                );
            }
        } else {
            $select[] = DB::raw(
                "'-' AS indikator"
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DATA AUDITEE
        |--------------------------------------------------------------------------
        */

        if (
            Schema::hasTable('users')
            && Schema::hasColumn(
                'penerapan_standar',
                'id_user'
            )
        ) {
            $query->leftJoin(
                'users as auditee',
                'auditee.id',
                '=',
                'ps.id_user'
            );

            if (
                Schema::hasColumn(
                    'users',
                    'nama'
                )
            ) {
                $select[] =
                    'auditee.nama as nama_auditee';
            } else {
                $select[] = DB::raw(
                    "'-' AS nama_auditee"
                );
            }
        } else {
            $select[] = DB::raw(
                "'-' AS nama_auditee"
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DATA TEMUAN
        |--------------------------------------------------------------------------
        */

        if (
            Schema::hasTable('temuan_ami')
            && Schema::hasColumn(
                'temuan_ami',
                'id_penerapan_standar'
            )
        ) {
            $query->leftJoin(
                'temuan_ami as ta',
                'ta.id_penerapan_standar',
                '=',
                'ps.id'
            );

            $select[] = 'ta.id as id_temuan';

            if (
                Schema::hasColumn(
                    'temuan_ami',
                    'status_temuan'
                )
            ) {
                $select[] = 'ta.status_temuan';
            } else {
                $select[] = DB::raw(
                    "NULL AS status_temuan"
                );
            }
        } else {
            $select[] = DB::raw(
                'NULL AS id_temuan'
            );

            $select[] = DB::raw(
                'NULL AS status_temuan'
            );
        }

        return $query
            ->select($select)
            ->orderByDesc('ps.id')
            ->limit(8)
            ->get();
    }

    /**
     * Mengambil temuan terbaru.
     */
    private function getTemuanTerbaru(
        $idPeriodeDitugaskan
    ) {
        if (
            $idPeriodeDitugaskan->isEmpty()
            || !Schema::hasTable('temuan_ami')
            || !Schema::hasTable('penerapan_standar')
            || !Schema::hasTable(
                'standarmutu_periodeami'
            )
            || !Schema::hasColumn(
                'temuan_ami',
                'id_penerapan_standar'
            )
            || !Schema::hasColumn(
                'penerapan_standar',
                'id_standarmutu_periodeami'
            )
            || !Schema::hasColumn(
                'standarmutu_periodeami',
                'id_periode_ami'
            )
        ) {
            return collect();
        }

        $query = DB::table('temuan_ami as ta')
            ->join(
                'penerapan_standar as ps',
                'ps.id',
                '=',
                'ta.id_penerapan_standar'
            )
            ->join(
                'standarmutu_periodeami as smp',
                'smp.id',
                '=',
                'ps.id_standarmutu_periodeami'
            )
            ->whereIn(
                'smp.id_periode_ami',
                $idPeriodeDitugaskan
            );

        $select = [
            'ta.id',
        ];

        if (
            Schema::hasColumn(
                'temuan_ami',
                'temuan'
            )
        ) {
            $select[] = 'ta.temuan';
        } else {
            $select[] = DB::raw(
                "'' AS temuan"
            );
        }

        if (
            Schema::hasColumn(
                'temuan_ami',
                'status_temuan'
            )
        ) {
            $select[] = 'ta.status_temuan';
        } else {
            $select[] = DB::raw(
                "'open' AS status_temuan"
            );
        }

        /*
        |--------------------------------------------------------------------------
        | INDIKATOR
        |--------------------------------------------------------------------------
        */

        if (
            Schema::hasTable('indikator_standar')
            && Schema::hasColumn(
                'penerapan_standar',
                'id_indikator'
            )
        ) {
            $query->leftJoin(
                'indikator_standar as indikator',
                'indikator.id',
                '=',
                'ps.id_indikator'
            );

            if (
                Schema::hasColumn(
                    'indikator_standar',
                    'deskripsi'
                )
            ) {
                $select[] =
                    'indikator.deskripsi as indikator';
            } else {
                $select[] = DB::raw(
                    "'-' AS indikator"
                );
            }
        } else {
            $select[] = DB::raw(
                "'-' AS indikator"
            );
        }

        /*
        |--------------------------------------------------------------------------
        | JUMLAH TANGGAPAN
        |--------------------------------------------------------------------------
        */

        if (
            Schema::hasTable('tanggapan_auditee')
            && Schema::hasColumn(
                'tanggapan_auditee',
                'id_temuan_ami'
            )
        ) {
            $select[] = DB::raw(
                "
                (
                    SELECT COUNT(*)
                    FROM tanggapan_auditee AS tg
                    WHERE tg.id_temuan_ami = ta.id
                ) AS jumlah_tanggapan
                "
            );
        } else {
            $select[] = DB::raw(
                '0 AS jumlah_tanggapan'
            );
        }

        return $query
            ->select($select)
            ->orderByRaw(
                "
                CASE
                    WHEN LOWER(
                        TRIM(
                            COALESCE(
                                ta.status_temuan,
                                ''
                            )
                        )
                    ) = 'open'
                    THEN 0
                    ELSE 1
                END
                "
            )
            ->orderByDesc('ta.id')
            ->limit(8)
            ->get();
    }

    /**
     * Mengambil ID Auditor dari session.
     */
    private function getLoginUserId(): int
    {
        $user = session('user');

        abort_if(
            !$user,
            401,
            'Sesi pengguna tidak ditemukan.'
        );

        $idUser = is_array($user)
            ? ($user['id'] ?? null)
            : ($user->id ?? null);

        abort_if(
            !$idUser,
            401,
            'ID pengguna tidak ditemukan pada sesi.'
        );

        return (int) $idUser;
    }
}