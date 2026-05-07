<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StandarMutu;

class StandarMutuController extends Controller
{
    public function index()
    {
        $data = StandarMutu::all();
        return view('standarmutu.index', compact('data'));
    }

    public function create()
    {
        return view('standarmutu.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_standar_mutu' => 'required'
        ]);

        StandarMutu::create([
            'nama_standar_mutu' => $request->nama_standar_mutu
        ]);

        return redirect()->route('standarmutu.index');
    }

    public function edit($id)
    {
        $data = StandarMutu::findOrFail($id);
        return view('standarmutu.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_standar_mutu' => 'required'
        ]);

        $data = StandarMutu::findOrFail($id);
        $data->update([
            'nama_standar_mutu' => $request->nama_standar_mutu
        ]);

        return redirect()->route('standarmutu.index');
    }

    public function show($id)
{
    $data = StandarMutu::findOrFail($id);
    return view('standarmutu.show', compact('data'));
}

    public function destroy($id)
    {
        StandarMutu::destroy($id);
        return redirect()->route('standarmutu.index');
    }
}