<?php

namespace App\Http\Controllers;

use App\Models\PeriodeAmi;
use App\Models\JadwalAmi;

class JadwalAuditorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Daftar Jadwal AMI
    |--------------------------------------------------------------------------
    */

    public function index($id)
    {
        $periodeAmi = PeriodeAmi::findOrFail($id);

        $data = JadwalAmi::where(
            'id_periode_ami',
            $id
        )->orderBy('id')->get();

        return view(
            'auditor.periode.jadwal.index',
            compact(
                'periodeAmi',
                'data'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Detail Jadwal
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $jadwal = JadwalAmi::findOrFail($id);

        $periodeAmi = PeriodeAmi::findOrFail(
            $jadwal->id_periode_ami
        );

        return view(
            'auditor.periode.jadwal.show',
            compact(
                'jadwal',
                'periodeAmi'
            )
        );
    }
}