<?php

namespace App\Http\Controllers;

use App\Models\RekomendasiPeningkatan;
use App\Models\PenerapanStandar;
use Illuminate\Http\Request;

class RekomendasiAuditorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $data = RekomendasiPeningkatan::with([

            'penerapan',

            'user'

        ])
        ->orderBy('id','desc')
        ->get();

        return view(
            'auditor.rekomendasi.index',
            compact('data')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $penerapan = PenerapanStandar::with([

            'standarmutuPeriode',

            'standarmutuPeriode.standarMutu'

        ])
        ->orderBy('id','desc')
        ->get();

        return view(
            'auditor.rekomendasi.create',
            compact('penerapan')
        );
    }
    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([

            'id_penerapan_standar' => 'required|exists:penerapan_standar,id',

            'aspek' => 'required',

            'kelebihan' => 'required',

            'rekomendasi' => 'required'

        ]);

        $user = session('user');

        $idUser = is_array($user)
            ? $user['id']
            : $user->id;

        RekomendasiPeningkatan::create([

            'id_penerapan_standar' => $request->id_penerapan_standar,

            'aspek' => $request->aspek,

            'kelebihan' => $request->kelebihan,

            'rekomendasi' => $request->rekomendasi,

            'id_user' => $idUser

        ]);

        return redirect()

            ->route('auditor.rekomendasi.index')

            ->with(
                'success',
                'Rekomendasi Peningkatan berhasil ditambahkan.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $data = RekomendasiPeningkatan::with([

            'penerapan',

            'penerapan.standarmutuPeriode',

            'penerapan.standarmutuPeriode.standarMutu',

            'user'

        ])->findOrFail($id);

        return view(
            'auditor.rekomendasi.show',
            compact('data')
        );
    }

        /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $data = RekomendasiPeningkatan::findOrFail($id);

        $penerapan = PenerapanStandar::with([

            'standarmutuPeriode',

            'standarmutuPeriode.standarMutu'

        ])
        ->orderBy('id','desc')
        ->get();

        return view(
            'auditor.rekomendasi.edit',
            compact(
                'data',
                'penerapan'
            )
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

            'id_penerapan_standar' => 'required|exists:penerapan_standar,id',

            'aspek' => 'required',

            'kelebihan' => 'required',

            'rekomendasi' => 'required'

        ]);

        $data = RekomendasiPeningkatan::findOrFail($id);

        $data->update([

            'id_penerapan_standar' => $request->id_penerapan_standar,

            'aspek' => $request->aspek,

            'kelebihan' => $request->kelebihan,

            'rekomendasi' => $request->rekomendasi

        ]);

        return redirect()

            ->route('auditor.rekomendasi.index')

            ->with(
                'success',
                'Rekomendasi Peningkatan berhasil diperbarui.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $data = RekomendasiPeningkatan::findOrFail($id);

        $data->delete();

        return redirect()

            ->route('auditor.rekomendasi.index')

            ->with(
                'success',
                'Rekomendasi Peningkatan berhasil dihapus.'
            );
    }
}