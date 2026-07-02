<?php

namespace App\Http\Controllers;

use App\Models\StandarMutu;

class StandarMutuAuditorController extends Controller
{
    public function index()
    {
        $data = StandarMutu::orderBy('id', 'desc')->get();

        return view('auditor.standarmutu.index', compact('data'));
    }

    public function show($id)
    {
        $standar = StandarMutu::findOrFail($id);

        return view('auditor.standarmutu.show', compact('standar'));
    }
}