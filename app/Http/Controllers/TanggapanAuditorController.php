<?php

namespace App\Http\Controllers;

use App\Models\TanggapanAuditee;

class TanggapanAuditorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DAFTAR TANGGAPAN AUDITEE
    |--------------------------------------------------------------------------
    |
    | Auditor hanya dapat melihat tanggapan yang berasal dari periode
    | tempat dirinya terdaftar sebagai Tim AMI.
    |
    */

    public function index()
    {
        $auditorId = $this->getLoginAuditorId();

        $data = TanggapanAuditee::with([
            'user',

            'temuan',
            'temuan.penerapanStandar',
            'temuan.penerapanStandar.user',
            'temuan.penerapanStandar.indikator',

            'temuan.penerapanStandar.standarmutuPeriode',
            'temuan.penerapanStandar.standarmutuPeriode.standarMutu',
            'temuan.penerapanStandar.standarmutuPeriode.periodeAmi',
            'temuan.penerapanStandar.standarmutuPeriode.periodeAmi.unitKerja',
            'temuan.penerapanStandar.standarmutuPeriode.periodeAmi.tim',
            'temuan.penerapanStandar.standarmutuPeriode.periodeAmi.tim.user',
        ])
            ->whereHas(
                'temuan.penerapanStandar.standarmutuPeriode.periodeAmi.tim',
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
            'auditor.tanggapan.index',
            compact('data')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL TANGGAPAN AUDITEE
    |--------------------------------------------------------------------------
    |
    | Auditor tidak dapat membuka detail tanggapan dari periode lain hanya
    | dengan mengganti ID pada URL.
    |
    */

    public function show($id)
    {
        $auditorId = $this->getLoginAuditorId();

        $tanggapan = TanggapanAuditee::with([
            'user',

            'temuan',
            'temuan.penerapanStandar',
            'temuan.penerapanStandar.user',
            'temuan.penerapanStandar.indikator',

            'temuan.penerapanStandar.standarmutuPeriode',
            'temuan.penerapanStandar.standarmutuPeriode.standarMutu',
            'temuan.penerapanStandar.standarmutuPeriode.periodeAmi',
            'temuan.penerapanStandar.standarmutuPeriode.periodeAmi.unitKerja',
            'temuan.penerapanStandar.standarmutuPeriode.periodeAmi.tim',
            'temuan.penerapanStandar.standarmutuPeriode.periodeAmi.tim.user',
        ])
            ->whereHas(
                'temuan.penerapanStandar.standarmutuPeriode.periodeAmi.tim',
                function ($query) use ($auditorId) {
                    $query->where(
                        'id_user',
                        $auditorId
                    );
                }
            )
            ->findOrFail($id);

        return view(
            'auditor.tanggapan.show',
            compact('tanggapan')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | AMBIL ID AUDITOR YANG LOGIN
    |--------------------------------------------------------------------------
    |
    | Sistem proyek menyimpan data login pada session('user').
    | Session dapat berbentuk object ataupun array.
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