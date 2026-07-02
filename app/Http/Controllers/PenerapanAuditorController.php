<?php

namespace App\Http\Controllers;

use App\Models\PeriodeAmi;
use App\Models\PenerapanStandar;

class PenerapanAuditorController extends Controller
{
    public function index($id)
    {
        $periodeAmi = PeriodeAmi::findOrFail($id);

        $data = PenerapanStandar::with([
            'user',
            'standarMutuPeriodeAmi.standarMutu'
        ])
        ->whereHas(
            'standarMutuPeriodeAmi',
            function($q) use($id){

                $q->where(
                    'id_periode_ami',
                    $id
                );

            }
        )
        ->get();

        return view(
            'auditor.periode.penerapan.index',
            compact(
                'periodeAmi',
                'data'
            )
        );
    }

    public function show($id,$penerapan)
    {
        $periodeAmi = PeriodeAmi::findOrFail($id);

        $data = PenerapanStandar::with([
            'user',
            'standarMutuPeriodeAmi.standarMutu'
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