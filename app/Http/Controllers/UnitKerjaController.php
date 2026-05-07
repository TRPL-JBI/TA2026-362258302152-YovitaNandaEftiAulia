<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UnitKerja;

class UnitKerjaController extends Controller
{
    public function index()
    {
        $data = UnitKerja::all();
        return view('unit.index', compact('data'));
    }

    public function create()
    {
        return view('unit.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'kategori_unit_kerja' => 'required'
        ]);

        UnitKerja::create([
            'nama' => $request->nama,
            'kategori_unit_kerja' => $request->kategori_unit_kerja,
        ]);

        return redirect()->route('unit-kerja.index');
    }

    public function show($id)
    {
        $data = UnitKerja::findOrFail($id);
        return view('unit.show', compact('data'));
    }

    public function edit($id)
    {
        $data = UnitKerja::findOrFail($id);
        return view('unit.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'kategori_unit_kerja' => 'required'
        ]);

        $data = UnitKerja::findOrFail($id);

        $data->update([
            'nama' => $request->nama,
            'kategori_unit_kerja' => $request->kategori_unit_kerja,
        ]);

        return redirect()->route('unit-kerja.index');
    }

    public function delete($id)
    {
        $data = UnitKerja::findOrFail($id);
        return view('unit.delete', compact('data'));
    }

    public function destroy($id)
    {
        UnitKerja::destroy($id);
        return redirect()->route('unit-kerja.index');
    }
}