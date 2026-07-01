<?php

namespace App\Http\Controllers;

use App\Models\PeriodeAmi;
use App\Models\PenerapanStandar;

class PenerapanStandarController extends Controller
{
    public function index($id)
    {
        $periode = PeriodeAmi::with([
            'standarMutu',
            'unitKerja'
        ])->findOrFail($id);

        $data = PenerapanStandar::with([
            'user',
            'standarMutuPeriodeAmi.standarMutu',
            'standarMutuPeriodeAmi.periodeAmi'
        ])
        ->whereHas(
            'standarMutuPeriodeAmi',
            function ($q) use ($id) {

                $q->where(
                    'id_periode_ami',
                    $id
                );
            }
        )
        ->get();

        return view(
            'penerapan.index',
            compact(
                'periode',
                'data'
            )
        );
    }

    public function show($id,$penerapan)
    {
        $periode = PeriodeAmi::findOrFail($id);

        $data = PenerapanStandar::with([
            'user',
            'standarMutuPeriodeAmi.standarMutu'
        ])
        ->findOrFail($penerapan);

        return view(
            'penerapan.show',
            compact(
                'periode',
                'data'
            )
        );
    }
}