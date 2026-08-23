<?php

namespace App\Http\Controllers;

use App\Models\PeriodeAmi;
use App\Models\TimAmi;

class TimAuditorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Mengambil ID Auditor yang Login
    |--------------------------------------------------------------------------
    */

    private function getAuditorId(): int
    {
        $auditorId = session('user_id');

        abort_if(
            empty($auditorId),
            403,
            'Sesi auditor tidak ditemukan. Silakan login kembali.'
        );

        return (int) $auditorId;
    }


    /*
    |--------------------------------------------------------------------------
    | Memastikan Auditor Ditugaskan pada Periode AMI
    |--------------------------------------------------------------------------
    |
    | Auditor hanya dapat mengakses periode AMI yang memang
    | menjadi penugasannya berdasarkan tabel tim_ami.
    |
    */

    private function getPeriodeAuditor(
        int $id
    ): PeriodeAmi {

        $auditorId = $this->getAuditorId();

        return PeriodeAmi::with([
            'standarMutu',
            'unitKerja',
            'user',
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
    }


    /*
    |--------------------------------------------------------------------------
    | Daftar Tim Auditor
    |--------------------------------------------------------------------------
    */

    public function index($id)
    {
        /*
        |--------------------------------------------------------------------------
        | Pastikan periode AMI memang menjadi
        | penugasan auditor yang sedang login.
        |--------------------------------------------------------------------------
        */

        $periodeAmi = $this->getPeriodeAuditor(
            (int) $id
        );


        /*
        |--------------------------------------------------------------------------
        | Setelah periode dinyatakan valid,
        | tampilkan tim pada periode tersebut.
        |--------------------------------------------------------------------------
        */

        $data = TimAmi::with('user')
            ->where(
                'id_periode_ami',
                $periodeAmi->id
            )
            ->orderBy('id')
            ->get();


        return view(
            'auditor.periode.tim.index',
            compact(
                'periodeAmi',
                'data'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Detail Tim Auditor
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        /*
        |--------------------------------------------------------------------------
        | Ambil data tim terlebih dahulu.
        |--------------------------------------------------------------------------
        */

        $tim = TimAmi::with([
            'user',
            'periode',
        ])->findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Pastikan auditor yang login memang ditugaskan
        | pada periode dari tim tersebut.
        |--------------------------------------------------------------------------
        */

        $periodeAmi = $this->getPeriodeAuditor(
            (int) $tim->id_periode_ami
        );


        return view(
            'auditor.periode.tim.show',
            compact(
                'tim',
                'periodeAmi'
            )
        );
    }
}