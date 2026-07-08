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
    */

    public function index($id)
    {
        $periodeAmi = PeriodeAmi::findOrFail($id);

        $data = PenerapanStandar::with([

            'user',

            'standarmutuPeriode.standarMutu'

        ])
        ->whereHas(
            'standarmutuPeriode',
            function ($q) use ($id) {

                $q->where(
                    'id_periode_ami',
                    $id
                );

            }
        )
        ->orderBy('id', 'desc')
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
    */

    public function show($id, $penerapan)
    {
        $periodeAmi = PeriodeAmi::findOrFail($id);

        $data = PenerapanStandar::with([

            'user',

            'standarmutuPeriode.standarMutu'

        ])->findOrFail($penerapan);

        return view(
            'auditor.periode.penerapan.show',
            compact(
                'periodeAmi',
                'data'
            )
        );
    }
}