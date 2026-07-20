<?php

namespace App\Http\Controllers;

use App\Models\PeriodeAmi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardAuditeeController extends Controller
{
    /**
     * Menampilkan dashboard Auditee.
     */
    public function index()
    {
        $user = $this->currentUser();

        $idUser = $user['id'];

        $idUnitKerja = $user['id_unit_kerja'];

        /*
        |--------------------------------------------------------------------------
        | JUMLAH PENERAPAN AUDITEE
        |--------------------------------------------------------------------------
        */

        $penerapanSaya = DB::table('penerapan_standar')
            ->where('id_user', $idUser)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | JUMLAH BUKTI PENDUKUNG
        |--------------------------------------------------------------------------
        |
        | Bukti dianggap tersedia ketika kolom link_bukti tidak NULL
        | dan tidak berupa string kosong.
        |
        */

        $buktiSaya = DB::table('penerapan_standar')
            ->where('id_user', $idUser)
            ->whereNotNull('link_bukti')
            ->whereRaw("TRIM(link_bukti) <> ''")
            ->count();

        /*
        |--------------------------------------------------------------------------
        | QUERY DASAR TEMUAN MILIK AUDITEE
        |--------------------------------------------------------------------------
        |
        | Temuan milik Auditee ditentukan dari penerapan_standar.id_user.
        |
        */

        $temuanDasar = DB::table('temuan_ami as ta')
            ->join(
                'penerapan_standar as ps',
                'ps.id',
                '=',
                'ta.id_penerapan_standar'
            )
            ->where('ps.id_user', $idUser);

        /*
        |--------------------------------------------------------------------------
        | TOTAL TEMUAN
        |--------------------------------------------------------------------------
        */

        $temuanSaya = (clone $temuanDasar)
            ->distinct()
            ->count('ta.id');

        /*
        |--------------------------------------------------------------------------
        | TEMUAN OPEN
        |--------------------------------------------------------------------------
        */

        $temuanOpen = (clone $temuanDasar)
            ->whereRaw(
                "LOWER(TRIM(COALESCE(ta.status_temuan, ''))) = ?",
                ['open']
            )
            ->distinct()
            ->count('ta.id');

        /*
        |--------------------------------------------------------------------------
        | TEMUAN CLOSED
        |--------------------------------------------------------------------------
        */

        $temuanClosed = (clone $temuanDasar)
            ->whereRaw(
                "LOWER(TRIM(COALESCE(ta.status_temuan, ''))) = ?",
                ['closed']
            )
            ->distinct()
            ->count('ta.id');

        /*
        |--------------------------------------------------------------------------
        | TABEL TANGGAPAN
        |--------------------------------------------------------------------------
        |
        | Proyek menggunakan tabel tanggapan_auditee.
        | Pengecekan Schema dibuat agar dashboard tetap terbuka apabila
        | tabel tanggapan belum dibuat.
        |
        */

        $tabelTanggapanAda = Schema::hasTable(
            'tanggapan_auditee'
        );

        $kolomTemuanTanggapanAda =
            $tabelTanggapanAda
            && Schema::hasColumn(
                'tanggapan_auditee',
                'id_temuan_ami'
            );

        $kolomUserTanggapanAda =
            $tabelTanggapanAda
            && Schema::hasColumn(
                'tanggapan_auditee',
                'id_user'
            );

        /*
        |--------------------------------------------------------------------------
        | JUMLAH TANGGAPAN AUDITEE
        |--------------------------------------------------------------------------
        */

        if ($tabelTanggapanAda) {
            $queryTanggapan = DB::table(
                'tanggapan_auditee'
            );

            if ($kolomUserTanggapanAda) {
                $queryTanggapan->where(
                    'id_user',
                    $idUser
                );
            } elseif ($kolomTemuanTanggapanAda) {
                $queryTanggapan
                    ->join(
                        'temuan_ami as ta',
                        'ta.id',
                        '=',
                        'tanggapan_auditee.id_temuan_ami'
                    )
                    ->join(
                        'penerapan_standar as ps',
                        'ps.id',
                        '=',
                        'ta.id_penerapan_standar'
                    )
                    ->where(
                        'ps.id_user',
                        $idUser
                    );
            }

            $jumlahTanggapan = $queryTanggapan->count();
        } else {
            $jumlahTanggapan = 0;
        }

        /*
        |--------------------------------------------------------------------------
        | TEMUAN BELUM DITANGGAPI
        |--------------------------------------------------------------------------
        */

        if ($kolomTemuanTanggapanAda) {
            $temuanBelumDitanggapi = DB::table(
                'temuan_ami as ta'
            )
                ->join(
                    'penerapan_standar as ps',
                    'ps.id',
                    '=',
                    'ta.id_penerapan_standar'
                )
                ->leftJoin(
                    'tanggapan_auditee as tg',
                    'tg.id_temuan_ami',
                    '=',
                    'ta.id'
                )
                ->where(
                    'ps.id_user',
                    $idUser
                )
                ->whereRaw(
                    "LOWER(TRIM(COALESCE(ta.status_temuan, ''))) = ?",
                    ['open']
                )
                ->whereNull('tg.id')
                ->distinct()
                ->count('ta.id');
        } else {
            $temuanBelumDitanggapi = $temuanOpen;
        }

        /*
        |--------------------------------------------------------------------------
        | DAFTAR TEMUAN TERBARU
        |--------------------------------------------------------------------------
        */

        $daftarTemuanQuery = DB::table(
            'temuan_ami as ta'
        )
            ->join(
                'penerapan_standar as ps',
                'ps.id',
                '=',
                'ta.id_penerapan_standar'
            )
            ->leftJoin(
                'indikator_standar as indikator',
                'indikator.id',
                '=',
                'ps.id_indikator'
            )
            ->where(
                'ps.id_user',
                $idUser
            );

        if ($kolomTemuanTanggapanAda) {
            $daftarTemuanQuery
                ->leftJoin(
                    'tanggapan_auditee as tg',
                    'tg.id_temuan_ami',
                    '=',
                    'ta.id'
                )
                ->select([
                    'ta.id',
                    'ta.temuan',
                    'ta.status_temuan',
                    'indikator.deskripsi as indikator',

                    DB::raw(
                        'COUNT(DISTINCT tg.id) AS jumlah_tanggapan'
                    ),
                ])
                ->groupBy(
                    'ta.id',
                    'ta.temuan',
                    'ta.status_temuan',
                    'indikator.deskripsi'
                );
        } else {
            $daftarTemuanQuery->select([
                'ta.id',
                'ta.temuan',
                'ta.status_temuan',
                'indikator.deskripsi as indikator',

                DB::raw(
                    '0 AS jumlah_tanggapan'
                ),
            ]);
        }

        $daftarTemuan = $daftarTemuanQuery
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

        /*
        |--------------------------------------------------------------------------
        | PERIODE AMI BERJALAN
        |--------------------------------------------------------------------------
        |
        | Hanya menampilkan periode milik unit kerja Auditee.
        |
        */

        $periodeBerjalan = PeriodeAmi::with([
            'standarMutu',
            'unitKerja',
        ])
            ->where(
                'id_unit_kerja',
                $idUnitKerja
            )
            ->whereRaw(
                "LOWER(TRIM(COALESCE(status, ''))) = ?",
                ['berjalan']
            )
            ->orderByDesc('tahun')
            ->orderByDesc('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | PERSENTASE PENYELESAIAN
        |--------------------------------------------------------------------------
        */

        $persentasePenyelesaian =
            $temuanSaya > 0
                ? round(
                    ($temuanClosed / $temuanSaya) * 100,
                    2
                )
                : 0;

        /*
        |--------------------------------------------------------------------------
        | TAMPILKAN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'auditee.dashboard',
            compact(
                'penerapanSaya',
                'buktiSaya',
                'temuanSaya',
                'temuanOpen',
                'temuanClosed',
                'jumlahTanggapan',
                'temuanBelumDitanggapi',
                'daftarTemuan',
                'periodeBerjalan',
                'persentasePenyelesaian'
            )
        );
    }

    /**
     * Mengambil data pengguna yang sedang login.
     */
    private function currentUser(): array
    {
        $sessionUser = session('user');

        abort_unless(
            $sessionUser,
            401,
            'Sesi pengguna tidak ditemukan. Silakan login kembali.'
        );

        $idUser = is_array($sessionUser)
            ? ($sessionUser['id'] ?? null)
            : ($sessionUser->id ?? null);

        $idUnitKerja = is_array($sessionUser)
            ? ($sessionUser['id_unit_kerja'] ?? null)
            : ($sessionUser->id_unit_kerja ?? null);

        abort_unless(
            $idUser,
            401,
            'ID pengguna tidak ditemukan pada sesi.'
        );

        abort_unless(
            $idUnitKerja,
            403,
            'Unit kerja pengguna belum ditentukan.'
        );

        return [
            'id' => (int) $idUser,
            'id_unit_kerja' => (int) $idUnitKerja,
        ];
    }
}