<?php

namespace App\Http\Controllers;

use App\Models\PeriodeAmi;

class PeriodeAuditorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DAFTAR PERIODE AMI AUDITOR
    |--------------------------------------------------------------------------
    |
    | Auditor hanya boleh melihat periode AMI tempat dirinya terdaftar
    | sebagai anggota Tim AMI.
    |
    */

    public function index()
    {
        $auditorId = $this->getLoginAuditorId();

        $data = PeriodeAmi::with([
            'standarMutu',
            'unitKerja',
            'user',
            'tim',
            'tim.user',
        ])
            ->whereHas(
                'tim',
                function ($query) use ($auditorId) {
                    $query->where(
                        'id_user',
                        $auditorId
                    );
                }
            )
            ->orderByDesc('id')
            ->get();

        return view(
            'auditor.periode.index',
            compact('data')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL PERIODE AMI AUDITOR
    |--------------------------------------------------------------------------
    |
    | Detail periode hanya dapat dibuka apabila Auditor sedang ditugaskan
    | pada periode tersebut.
    |
    | Apabila Auditor mencoba mengganti ID pada URL ke periode lain,
    | Laravel akan menghasilkan halaman 404.
    |
    */

    public function show($id)
    {
        $auditorId = $this->getLoginAuditorId();

        $periode = PeriodeAmi::with([
            'standarMutu',
            'unitKerja',
            'user',
            'tim',
            'tim.user',
            'jadwal',
            'standarMutuPeriode',
        ])
            ->whereHas(
                'tim',
                function ($query) use ($auditorId) {
                    $query->where(
                        'id_user',
                        $auditorId
                    );
                }
            )
            ->findOrFail($id);

        return view(
            'auditor.periode.show',
            compact('periode')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | AMBIL ID AUDITOR YANG LOGIN
    |--------------------------------------------------------------------------
    |
    | Sistem login proyek menyimpan data user pada session('user').
    | Nilainya bisa berupa object atau array, sehingga keduanya ditangani.
    |
    */

    private function getLoginAuditorId(): int
    {
        $user = session('user');

        abort_if(
            !$user,
            401,
            'Sesi pengguna tidak ditemukan. Silakan login kembali.'
        );

        $userId = is_array($user)
            ? ($user['id'] ?? null)
            : ($user->id ?? null);

        $role = is_array($user)
            ? ($user['role'] ?? null)
            : ($user->role ?? null);

        abort_if(
            !$userId,
            401,
            'ID pengguna pada sesi tidak ditemukan.'
        );

        abort_unless(
            $role === 'auditor',
            403,
            'Halaman ini hanya dapat diakses oleh Auditor.'
        );

        return (int) $userId;
    }
}