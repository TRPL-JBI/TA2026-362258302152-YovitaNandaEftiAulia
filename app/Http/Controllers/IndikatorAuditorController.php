<?php

namespace App\Http\Controllers;

use App\Models\IsiStandarMutu;
use App\Models\IndikatorStandar;

class IndikatorAuditorController extends Controller
{
    public function index($id)
    {
        $isi = IsiStandarMutu::findOrFail($id);

        $data = IndikatorStandar::where(
            'id_isi_standar_mutu',
            $id
        )->get();

        return view(
            'auditor.indikator.index',
            compact(
                'isi',
                'data'
            )
        );
    }

    public function show($id)
    {
        $indikator = IndikatorStandar::findOrFail($id);

        return view(
            'auditor.indikator.show',
            compact('indikator')
        );
    }
}