<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IsiStandarMutu;

class IsiStandarMutuController extends Controller
{
    // ================= KATEGORI =================
    public function kategori($standar_id)
    {
        $data = IsiStandarMutu::where('id_standar_mutu', $standar_id)
            ->whereNull('parent_standar_id')
            ->get();

        return view('isi.kategori', compact('data', 'standar_id'));
    }

    // ================= JENIS =================
    public function jenis($id)
    {
        $parent = IsiStandarMutu::findOrFail($id);

        $data = IsiStandarMutu::where('parent_standar_id', $id)->get();

        return view('isi.jenis', compact('data', 'parent'));
    }

    // ================= SUB =================
    public function sub($id)
    {
        $parent = IsiStandarMutu::findOrFail($id);

        $data = IsiStandarMutu::where('parent_standar_id', $id)->get();

        return view('isi.sub', compact('data', 'parent'));
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        IsiStandarMutu::create([
            'id_standar_mutu' => $request->id_standar_mutu,
            'nama_standar' => $request->nama_standar,
            'parent_standar_id' => $request->parent_standar_id
        ]);

        return back();
    }

    // ================= DETAIL =================
    public function show($id)
    {
        $data = IsiStandarMutu::findOrFail($id);
        return view('isi.show', compact('data'));
    }

    // ================= EDIT =================
    public function edit($id)
    {
        $data = IsiStandarMutu::findOrFail($id);
        return view('isi.edit', compact('data'));
    }

    // ================= UPDATE =================
    public function update(Request $request, $id)
    {
        $data = IsiStandarMutu::findOrFail($id);

        $data->update([
            'nama_standar' => $request->nama_standar
        ]);

        return redirect()->back();
    }

    // ================= DELETE =================
    public function destroy($id)
    {
        IsiStandarMutu::destroy($id);
        return back();
    }
}