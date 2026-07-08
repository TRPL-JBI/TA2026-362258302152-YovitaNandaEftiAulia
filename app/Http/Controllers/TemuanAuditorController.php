<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TemuanAmi;
use App\Models\PertanyaanAmi;

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
            'pertanyaan.penerapan',
            'pertanyaan.penerapan.standarmutuPeriode',
            'pertanyaan.penerapan.standarmutuPeriode.periodeAmi',
            'pertanyaan.penerapan.standarmutuPeriode.standarMutu',
            'pertanyaan.penerapan.standarmutuPeriode.periodeAmi.unitKerja'
        ])
        ->orderBy('id','desc')
        ->get();

        return view(
            'auditor.temuan.index',
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
        $pertanyaan = PertanyaanAmi::orderBy('pertanyaan')
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
        $temuan = TemuanAmi::with([

            'pertanyaan',

            'tanggapan',

            'akarMasalah'

        ])->findOrFail($id);

        return view(
            'auditor.temuan.show',
            compact('temuan')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $temuan = TemuanAmi::findOrFail($id);

        $pertanyaan = PertanyaanAmi::orderBy('pertanyaan')
                        ->get();

        return view(
            'auditor.temuan.edit',
            compact(
                'temuan',
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

        $temuan = TemuanAmi::findOrFail($id);

        $temuan->update([

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
        $temuan = TemuanAmi::findOrFail($id);

        $temuan->delete();

        return redirect()
            ->route('auditor.temuan.index')
            ->with(
                'success',
                'Temuan Audit berhasil dihapus.'
            );
    }
}