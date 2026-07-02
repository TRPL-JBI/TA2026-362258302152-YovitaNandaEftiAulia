<?php

namespace App\Http\Controllers;

use App\Models\TemuanAmi;
use App\Models\PertanyaanAmi;
use Illuminate\Http\Request;

class TemuanAuditorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $data = TemuanAmi::with([
            'pertanyaan',
            'pertanyaan.penerapanStandar',
            'pertanyaan.penerapanStandar.standarMutuPeriodeAmi',
            'pertanyaan.penerapanStandar.standarMutuPeriodeAmi.standarMutu',
            'pertanyaan.penerapanStandar.standarMutuPeriodeAmi.periodeAmi',
            'pertanyaan.penerapanStandar.standarMutuPeriodeAmi.periodeAmi.unitKerja'
        ])
        ->orderBy('id', 'desc')
        ->get();

        return view('auditor.temuan.index', compact('data'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

   public function create()
{
    $pertanyaan = PertanyaanAmi::with([

        'penerapanStandar',

        'penerapanStandar.standarMutuPeriodeAmi',

        'penerapanStandar.standarMutuPeriodeAmi.standarMutu',

        'penerapanStandar.standarMutuPeriodeAmi.periodeAmi',

        'penerapanStandar.standarMutuPeriodeAmi.periodeAmi.unitKerja'

    ])
    ->orderBy('id','desc')
    ->get();

    return view(
        'auditor.temuan.create',
        compact('pertanyaan')
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

            'id_pertanyaan' => 'required',

            'temuan' => 'required',

            'status_temuan' => 'required'

        ]);

        TemuanAmi::create([

            'id_pertanyaan' => $request->id_pertanyaan,

            'temuan' => $request->temuan,

            'status_temuan' => $request->status_temuan

        ]);

        return redirect()
            ->route('auditor.temuan.index')
            ->with(
              'success',
              'Temuan Audit berhasil ditambahkan.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $data = TemuanAmi::with([
            'pertanyaan',
            'pertanyaan.penerapanStandar',
            'pertanyaan.penerapanStandar.standarMutuPeriodeAmi',
            'pertanyaan.penerapanStandar.standarMutuPeriodeAmi.standarMutu',
            'pertanyaan.penerapanStandar.standarMutuPeriodeAmi.periodeAmi',
            'pertanyaan.penerapanStandar.standarMutuPeriodeAmi.periodeAmi.unitKerja'
        ])->findOrFail($id);

        return view(
            'auditor.temuan.show',
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
    $data = TemuanAmi::findOrFail($id);

    $pertanyaan = PertanyaanAmi::with([

        'penerapanStandar',

        'penerapanStandar.standarMutuPeriodeAmi',

        'penerapanStandar.standarMutuPeriodeAmi.standarMutu',

        'penerapanStandar.standarMutuPeriodeAmi.periodeAmi',

        'penerapanStandar.standarMutuPeriodeAmi.periodeAmi.unitKerja'

    ])
    ->orderBy('id','desc')
    ->get();

    return view(
        'auditor.temuan.edit',
        compact(
            'data',
            'pertanyaan'
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

            'id_pertanyaan' => 'required',

            'temuan' => 'required',

            'status_temuan' => 'required'

        ]);

        $data = TemuanAmi::findOrFail($id);

        $data->update([

            'id_pertanyaan' => $request->id_pertanyaan,

            'temuan' => $request->temuan,

            'status_temuan' => $request->status_temuan

        ]);

        return redirect()
             ->route('auditor.temuan.index')
             ->with(
                  'success',
                  'Temuan Audit berhasil diperbarui.'
           );
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $data = TemuanAmi::findOrFail($id);

        $data->delete();

        return redirect()
             ->route('auditor.temuan.index')
             ->with(
                  'success',
                  'Temuan Audit berhasil dihapus.'
    );
    }
}