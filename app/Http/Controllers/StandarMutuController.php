<?php

namespace App\Http\Controllers;

use App\Models\StandarMutu;
use Illuminate\Http\Request;

class StandarMutuController extends Controller
{

    /**
     * Menampilkan daftar Standar Mutu
     */
    public function index()
    {
        $standar = StandarMutu::orderBy('id', 'asc')->get();

        return view('standarmutu.index', compact('standar'));
    }

    /**
     * Form tambah
     */
    public function create()
    {
        return view('standarmutu.create');
    }

    /**
     * Simpan data
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_standar_mutu' => 'required|max:255'
        ],[
            'nama_standar_mutu.required'=>'Nama Standar Mutu wajib diisi.'
        ]);

        StandarMutu::create([
            'nama_standar_mutu'=>$request->nama_standar_mutu
        ]);

        return redirect()
            ->route('standarmutu.index')
            ->with('success','Standar Mutu berhasil ditambahkan.');
    }

    /**
     * Detail
     */
    public function show($id)
    {
        $standar = StandarMutu::findOrFail($id);

        return view('standarmutu.show', compact('standar'));
    }

    /**
     * Form edit
     */
    public function edit($id)
    {
        $standar = StandarMutu::findOrFail($id);

        return view('standarmutu.edit', compact('standar'));
    }

    /**
     * Update
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'nama_standar_mutu'=>'required|max:255'
        ]);

        $standar = StandarMutu::findOrFail($id);

        $standar->update([
            'nama_standar_mutu'=>$request->nama_standar_mutu
        ]);

        return redirect()
            ->route('standarmutu.index')
            ->with('success','Standar Mutu berhasil diperbarui.');
    }

    /**
     * Hapus
     */
    public function destroy($id)
    {
        $standar = StandarMutu::findOrFail($id);

        $standar->delete();

        return redirect()
            ->route('standarmutu.index')
            ->with('success','Standar Mutu berhasil dihapus.');
    }

}