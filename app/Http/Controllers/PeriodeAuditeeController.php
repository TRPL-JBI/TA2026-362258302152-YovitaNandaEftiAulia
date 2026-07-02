<?php

namespace App\Http\Controllers;

use App\Models\PeriodeAmi;

class PeriodeAuditeeController extends Controller
{
    public function index()
    {
        $data = PeriodeAmi::with([
            'standarMutu',
            'unitKerja'
        ])
        ->orderBy('tahun','desc')
        ->get();

        return view(
            'auditee.periode.index',
            compact('data')
        );
    }
}