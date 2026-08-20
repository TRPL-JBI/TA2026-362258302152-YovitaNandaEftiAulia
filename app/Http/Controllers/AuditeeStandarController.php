<?php

namespace App\Http\Controllers;

use App\Models\PenerapanStandar;
use App\Models\PeriodeAmi;
use App\Models\StandarMutuPeriodeAmi;
use App\Models\User;
use App\Services\StandarTableService;

class AuditeeStandarController extends Controller
{
    /**
     * Menampilkan Standar Mutu dan indikator yang memang menjadi
     * ruang lingkup penugasan Auditee yang sedang login.
     */
    public function index(
        $id,
        StandarTableService $service
    ) {
        $user = $this->currentUser();

        /*
        |--------------------------------------------------------------------------
        | CARI PERIODE YANG BENAR-BENAR MENJADI PENUGASAN AUDITEE
        |--------------------------------------------------------------------------
        |
        | Standar hanya dapat dibuka apabila:
        | - standar merupakan standar pada periode tersebut;
        | - periode sedang berjalan;
        | - user terdaftar di tim_ami sebagai auditee;
        | - unit kerja user termasuk ruang lingkup periode.
        |
        */
        $periode = PeriodeAmi::query()
            ->where(
                'id_standar_mutu',
                $id
            )
            ->whereHas(
                'tim',
                function ($query) use ($user) {
                    $query
                        ->where(
                            'id_user',
                            $user['id']
                        )
                        ->whereRaw(
                            "LOWER(TRIM(COALESCE(role, ''))) = ?",
                            ['auditee']
                        );
                }
            )
            ->where(
                function ($query) use ($user) {
                    $query
                        ->whereHas(
                            'unitKerjas',
                            function ($unitQuery) use ($user) {
                                $unitQuery->where(
                                    'unit_kerja.id',
                                    $user['id_unit_kerja']
                                );
                            }
                        )
                        ->orWhere(
                            'id_unit_kerja',
                            $user['id_unit_kerja']
                        );
                }
            )
            ->whereRaw(
                "LOWER(TRIM(COALESCE(status, ''))) = ?",
                ['berjalan']
            )
            ->orderByDesc('tahun')
            ->orderByDesc('id')
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | PASTIKAN RELASI STANDAR <-> PERIODE TERSEDIA
        |--------------------------------------------------------------------------
        |
        | Beberapa periode lama / periode yang dibuat sebelum revisi belum memiliki
        | record pada standarmutu_periodeami. Tanpa record ini halaman penerapan
        | akan berakhir Not Found. Karena periode dan standar sudah lolos validasi
        | hak akses di atas, relasi ini aman untuk dibuat / diaktifkan.
        |
        */
        $standarPeriode = StandarMutuPeriodeAmi::query()
            ->updateOrCreate(
                [
                    'id_standar_mutu' => (int) $id,
                    'id_periode_ami' => $periode->id,
                ],
                [
                    'status' => 'aktif',
                ]
            );

        /*
        |--------------------------------------------------------------------------
        | GENERATE TABEL STANDAR DAN INDIKATOR
        |--------------------------------------------------------------------------
        */
        $table = $service->generateTable($id);

        $standar = $table['standar'];
        $rows = $table['rows'];

        $maxLevel = collect($rows)
            ->map(
                fn ($row) => count($row['level'])
            )
            ->max() ?? 0;

        /*
        |--------------------------------------------------------------------------
        | DATA PENERAPAN MILIK AUDITEE PADA PERIODE INI SAJA
        |--------------------------------------------------------------------------
        */
        $penerapanByIndikator = PenerapanStandar::query()
            ->where(
                'id_standarmutu_periodeami',
                $standarPeriode->id
            )
            ->where(
                'id_user',
                $user['id']
            )
            ->get()
            ->keyBy('id_indikator');

        return view(
            'auditee.standar.index',
            compact(
                'standar',
                'rows',
                'maxLevel',
                'standarPeriode',
                'penerapanByIndikator'
            )
        );
    }

    /**
     * Mengambil Auditee aktif yang sedang login.
     */
    private function currentUser(): array
    {
        $user = request()
            ->attributes
            ->get('auth_user');

        if (!$user) {
            $user = User::query()->find(
                session('user_id')
            );
        }

        abort_unless(
            $user,
            401,
            'Sesi pengguna tidak ditemukan. Silakan login kembali.'
        );

        $status = strtolower(
            trim((string) $user->status)
        );

        $role = strtolower(
            trim((string) $user->role)
        );

        abort_unless(
            $status === 'aktif',
            403,
            'Akun tidak ditemukan atau sudah dinonaktifkan.'
        );

        abort_unless(
            $role === 'auditee',
            403,
            'Halaman ini hanya dapat diakses oleh Auditee.'
        );

        abort_unless(
            $user->id && $user->id_unit_kerja,
            403,
            'Data pengguna atau unit kerja belum lengkap.'
        );

        return [
            'id' => (int) $user->id,
            'id_unit_kerja' => (int) $user->id_unit_kerja,
        ];
    }
}