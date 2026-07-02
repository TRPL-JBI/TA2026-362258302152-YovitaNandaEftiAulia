<?php

namespace App\Http\Controllers;

use App\Models\PeriodeAmi;

class PeriodeAuditorController extends Controller
{
    public function index()
    {
        $data = PeriodeAmi::with([
            'standarMutu',
            'unitKerja',
            'user'
        ])->get();

        return view(
            'auditor.periode.index',
            compact('data')
        );
    }

    public function show($id)
    {
        $periode = PeriodeAmi::with([
            'standarMutu',
            'unitKerja',
            'user'
        ])->findOrFail($id);

        return view(
            'auditor.periode.show',
            compact('periode')
        );
    }
}