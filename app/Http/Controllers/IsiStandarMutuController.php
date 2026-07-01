<?php

namespace App\Http\Controllers;

use App\Models\StandarMutu;
use App\Models\IsiStandarMutu;
use Illuminate\Http\Request;

class IsiStandarMutuController extends Controller
{

    /**
     * Menampilkan daftar Isi Standar berdasarkan Standar Mutu
     */
    public function index($standar)
    {
        $standarMutu = StandarMutu::findOrFail($standar);

        $isiStandar = IsiStandarMutu::where(
            'id_standar_mutu',
            $standar
        )->orderBy('id')->get();

        return view(
            'isi_standar.index',
            compact(
                'standarMutu',
                'isiStandar'
            )
        );
    }

    /**
     * Form tambah
     */
    public function create($standar)
    {
        $standarMutu = StandarMutu::findOrFail($standar);

        $parent = IsiStandarMutu::where(
            'id_standar_mutu',
            $standar
        )->get();

        return view(
            'isi_standar.create',
            compact(
                'standarMutu',
                'parent'
            )
        );
    }

    /**
     * Simpan
     */
    public function store(Request $request,$standar)
    {

        $request->validate([
            'nama_standar'=>'required|max:255'
        ]);

        IsiStandarMutu::create([

            'id_standar_mutu'=>$standar,

            'nama_standar'=>$request->nama_standar,

            'parent_standar_id'=>$request->parent_standar_id

        ]);

        return redirect()
                ->route('isi.index',$standar)
                ->with('success','Isi Standar berhasil ditambahkan.');

    }

    /**
     * Edit
     */
    public function edit($isi)
    {

        $isiStandar = IsiStandarMutu::findOrFail($isi);

        $parent = IsiStandarMutu::where(
            'id_standar_mutu',
            $isiStandar->id_standar_mutu
        )->where(
            'id',
            '!=',
            $isi
        )->get();

        return view(
            'isi_standar.edit',
            compact(
                'isiStandar',
                'parent'
            )
        );

    }

    /**
     * Update
     */
    public function update(Request $request,$isi)
    {

        $request->validate([
            'nama_standar'=>'required|max:255'
        ]);

        $data = IsiStandarMutu::findOrFail($isi);

        $data->update([

            'nama_standar'=>$request->nama_standar,

            'parent_standar_id'=>$request->parent_standar_id

        ]);

        return redirect()
                ->route(
                    'isi.index',
                    $data->id_standar_mutu
                )
                ->with('success','Isi Standar berhasil diupdate.');

    }

    /**
     * Hapus
     */
    public function destroy($isi)
    {

        $data = IsiStandarMutu::findOrFail($isi);

        $standar = $data->id_standar_mutu;

        $data->delete();

        return redirect()
                ->route(
                    'isi.index',
                    $standar
                )
                ->with('success','Isi Standar berhasil dihapus.');

    }

    public function show($id)
{
    $isi = IsiStandarMutu::with([
        'standarMutu',
        'indikator'
    ])->findOrFail($id);

    return view(
        'isi_standar.show',
        compact('isi')
    );
}
}

