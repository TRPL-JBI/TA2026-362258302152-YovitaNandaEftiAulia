<?php

namespace App\Http\Controllers;

use App\Models\IsiStandarMutu;
use Illuminate\Http\Request;

class SubStandarController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index($isi)
    {
        $isiStandar = IsiStandarMutu::findOrFail($isi);

        $subStandar = IsiStandarMutu::where(
            'parent_standar_id',
            $isi
        )
        ->orderBy('id')
        ->get();

        return view(
            'sub_standar.index',
            compact(
                'isiStandar',
                'subStandar'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create($isi)
    {
        $isiStandar = IsiStandarMutu::findOrFail($isi);

        return view(
            'sub_standar.create',
            compact('isiStandar')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request, $isi)
    {
        $request->validate([

            'nama_standar' => 'required|max:255'

        ]);

        $parent = IsiStandarMutu::findOrFail($isi);

        IsiStandarMutu::create([

            'id_standar_mutu' => $parent->id_standar_mutu,

            'nama_standar' => $request->nama_standar,

            'parent_standar_id' => $isi

        ]);

        return redirect()
            ->route(
                'substandar.index',
                $isi
            )
            ->with(
                'success',
                'Sub Standar berhasil ditambahkan.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $subStandar = IsiStandarMutu::findOrFail($id);

        return view(
            'sub_standar.edit',
            compact('subStandar')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $request->validate([

            'nama_standar' => 'required|max:255'

        ]);

        $data = IsiStandarMutu::findOrFail($id);

        $data->update([

            'nama_standar' => $request->nama_standar

        ]);

        return redirect()
            ->route(
                'substandar.index',
                $data->parent_standar_id
            )
            ->with(
                'success',
                'Sub Standar berhasil diperbarui.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $data = IsiStandarMutu::findOrFail($id);

        $parent = $data->parent_standar_id;

        $data->delete();

        return redirect()
            ->route(
                'substandar.index',
                $parent
            )
            ->with(
                'success',
                'Sub Standar berhasil dihapus.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $subStandar = IsiStandarMutu::with([
            'indikator'
        ])->findOrFail($id);

        return view(
            'sub_standar.show',
            compact('subStandar')
        );
    }
}