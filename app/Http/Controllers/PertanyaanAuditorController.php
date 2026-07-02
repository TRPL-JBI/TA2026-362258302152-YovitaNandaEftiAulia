<?php

namespace App\Http\Controllers;

use App\Models\PeriodeAmi;
use App\Models\PertanyaanAmi;

class PertanyaanAuditorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Daftar Pertanyaan AMI
    |--------------------------------------------------------------------------
    */

    public function index($id)
    {
        $periodeAmi = PeriodeAmi::findOrFail($id);

        $data = PertanyaanAmi::with([
            'user',
            'penerapanStandar.standarMutuPeriodeAmi.standarMutu'
        ])
        ->whereHas(
            'penerapanStandar.standarMutuPeriodeAmi',
            function ($q) use ($id) {

                $q->where(
                    'id_periode_ami',
                    $id
                );

            }
        )
        ->get();

        return view(
            'auditor.periode.pertanyaan.index',
            compact(
                'periodeAmi',
                'data'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Detail Pertanyaan
    |--------------------------------------------------------------------------
    */

    public function show($id, $pertanyaan)
    {
        $periodeAmi = PeriodeAmi::findOrFail($id);

        $data = PertanyaanAmi::with([
            'user',
            'penerapanStandar.standarMutuPeriodeAmi.standarMutu'
        ])->findOrFail($pertanyaan);

        return view(
            'auditor.periode.pertanyaan.show',
            compact(
                'periodeAmi',
                'data'
            )
        );
    }
}