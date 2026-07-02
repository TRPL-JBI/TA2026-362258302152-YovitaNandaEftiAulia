<?php

namespace App\Http\Controllers;

use App\Models\StandarMutu;
use App\Models\IsiStandarMutu;

class IsiStandarAuditorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Daftar Isi Standar
    |--------------------------------------------------------------------------
    */

    public function index($id)
    {
        $standar = StandarMutu::findOrFail($id);

        $data = IsiStandarMutu::with('parent')
            ->where('id_standar_mutu', $id)
            ->orderBy('id')
            ->get();

        return view(
            'auditor.isi.index',
            compact(
                'standar',
                'data'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Detail Isi Standar
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $isi = IsiStandarMutu::with([
            'standarMutu',
            'parent',
            'indikator'
        ])->findOrFail($id);

        return view(
            'auditor.isi.show',
            compact('isi')
        );
    }
}