<?php

namespace App\Http\Controllers;

use App\Models\TanggapanAuditee;

class TanggapanAuditorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $data = TanggapanAuditee::with([
            'temuan',
            'user'
        ])
        ->orderBy('id','desc')
        ->get();

        return view(
            'auditor.tanggapan.index',
            compact('data')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $tanggapan = TanggapanAuditee::with([
            'temuan',
            'user'
        ])->findOrFail($id);

        return view(
            'auditor.tanggapan.show',
            compact('tanggapan')
        );
    }

}