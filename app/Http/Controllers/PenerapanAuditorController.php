<?php

namespace App\Http\Controllers;

use App\Models\PeriodeAmi;
use App\Models\PenerapanStandar;

class PenerapanAuditorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DAFTAR PENERAPAN STANDAR
    |--------------------------------------------------------------------------
    |
    | Auditor hanya dapat melihat penerapan pada periode AMI
    | tempat dirinya terdaftar sebagai Tim AMI.
    |
    */

    public function index($id)
    {
        $auditorId = $this->getLoginAuditorId();

        /*
        |--------------------------------------------------------------------------
        | VALIDASI PERIODE PENUGASAN
        |--------------------------------------------------------------------------
        |
        | Auditor tidak dapat membuka periode lain hanya dengan mengganti ID URL.
        |
        */

        $periodeAmi = PeriodeAmi::with([
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
            ->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | PENERAPAN DALAM PERIODE TERSEBUT
        |--------------------------------------------------------------------------
        */

        $data = PenerapanStandar::with([
            'user',
            'indikator',
            'standarmutuPeriode',
            'standarmutuPeriode.standarMutu',
            'standarmutuPeriode.periodeAmi',
        ])
            ->whereHas(
                'standarmutuPeriode',
                function ($query) use ($id) {
                    $query->where(
                        'id_periode_ami',
                        $id
                    );
                }
            )
            ->whereHas(
                'standarmutuPeriode.periodeAmi.tim',
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
            'auditor.periode.penerapan.index',
            compact(
                'periodeAmi',
                'data'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL PENERAPAN STANDAR
    |--------------------------------------------------------------------------
    |
    | Penerapan harus memenuhi dua syarat:
    |
    | 1. Penerapan memang berada dalam periode $id pada URL.
    | 2. Auditor yang login memang ditugaskan pada periode tersebut.
    |
    */

    public function show($id, $penerapan)
    {
        $auditorId = $this->getLoginAuditorId();

        /*
        |--------------------------------------------------------------------------
        | VALIDASI PERIODE PENUGASAN
        |--------------------------------------------------------------------------
        */

        $periodeAmi = PeriodeAmi::with([
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
            ->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | VALIDASI PENERAPAN
        |--------------------------------------------------------------------------
        |
        | findOrFail() hanya dijalankan setelah query dibatasi berdasarkan
        | periode URL dan penugasan Auditor.
        |
        */

        $data = PenerapanStandar::with([
            'user',
            'indikator',
            'standarmutuPeriode',
            'standarmutuPeriode.standarMutu',
            'standarmutuPeriode.periodeAmi',
            'standarmutuPeriode.periodeAmi.unitKerja',
            'standarmutuPeriode.periodeAmi.tim',
            'standarmutuPeriode.periodeAmi.tim.user',
        ])
            ->whereHas(
                'standarmutuPeriode',
                function ($query) use ($id) {
                    $query->where(
                        'id_periode_ami',
                        $id
                    );
                }
            )
            ->whereHas(
                'standarmutuPeriode.periodeAmi.tim',
                function ($query) use ($auditorId) {
                    $query->where(
                        'id_user',
                        $auditorId
                    );
                }
            )
            ->findOrFail($penerapan);

        return view(
            'auditor.periode.penerapan.show',
            compact(
                'periodeAmi',
                'data'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | AMBIL ID AUDITOR YANG LOGIN
    |--------------------------------------------------------------------------
    |
    | Proyek Anda menyimpan data login pada session('user').
    | Session dapat berbentuk object atau array.
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