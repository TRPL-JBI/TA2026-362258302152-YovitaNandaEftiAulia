<?php

namespace App\Http\Controllers;

use App\Models\PeriodeAmi;
use App\Models\PertanyaanAmi;
use App\Models\PenerapanStandar;
use Illuminate\Http\Request;

class PertanyaanAuditorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index($id)
    {
        $periode = PeriodeAmi::findOrFail($id);

        $data = PertanyaanAmi::with([
            'user',
            'penerapanStandar',
            'penerapanStandar.standarMutuPeriodeAmi',
            'penerapanStandar.standarMutuPeriodeAmi.standarMutu'
        ])
        ->whereHas(
            'penerapanStandar.standarMutuPeriodeAmi',
            function ($q) use ($id) {

                $q->where(
                    'id_periode_ami',
                    $id
                );

            }
        )
        ->get();

        return view(
            'auditor.pertanyaan.index',
            compact(
                'periode',
                'data'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create($id)
    {
        $periode = PeriodeAmi::findOrFail($id);

        $penerapan = PenerapanStandar::with([
            'standarMutuPeriodeAmi',
            'standarMutuPeriodeAmi.standarMutu'
        ])
        ->whereHas(
            'standarMutuPeriodeAmi',
            function ($q) use ($id) {

                $q->where(
                    'id_periode_ami',
                    $id
                );

            }
        )
        ->get();

        return view(
            'auditor.pertanyaan.create',
            compact(
                'periode',
                'penerapan'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request, $id)
    {
        $request->validate([

            'indikator' => 'required',

            'referensi' => 'required',

            'pertanyaan' => 'required',

            'id_penerapan_standar' =>
                'required|exists:penerapan_standar,id'

        ]);

        $user = session('user');

        $idUser = is_array($user)
            ? $user['id']
            : $user->id;

        PertanyaanAmi::create([

            'indikator' =>
                $request->indikator,

            'referensi' =>
                $request->referensi,

            'pertanyaan' =>
                $request->pertanyaan,

            'id_penerapan_standar' =>
                $request->id_penerapan_standar,

            'id_user' =>
                $idUser

        ]);

        return redirect()
            ->route(
                'auditor.pertanyaan.index',
                $id
            )
            ->with(
                'success',
                'Pertanyaan AMI berhasil ditambahkan.'
            );
    }

        /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show($pertanyaan)
    {
        $data = PertanyaanAmi::with([
            'user',
            'penerapanStandar',
            'penerapanStandar.standarMutuPeriodeAmi',
            'penerapanStandar.standarMutuPeriodeAmi.standarMutu',
            'penerapanStandar.standarMutuPeriodeAmi.periodeAmi'
        ])->findOrFail($pertanyaan);

        return view(
            'auditor.pertanyaan.show',
            compact('data')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($pertanyaan)
    {
        $data = PertanyaanAmi::with([
            'penerapanStandar',
            'penerapanStandar.standarMutuPeriodeAmi'
        ])->findOrFail($pertanyaan);

        $idPeriode = $data->penerapanStandar
            ->standarMutuPeriodeAmi
            ->id_periode_ami;

        $penerapan = PenerapanStandar::with([
            'standarMutuPeriodeAmi',
            'standarMutuPeriodeAmi.standarMutu'
        ])
        ->whereHas(
            'standarMutuPeriodeAmi',
            function ($q) use ($idPeriode) {

                $q->where(
                    'id_periode_ami',
                    $idPeriode
                );

            }
        )
        ->get();

        return view(
            'auditor.pertanyaan.edit',
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

    public function update(
        Request $request,
        $pertanyaan
    )
    {
        $request->validate([

            'indikator' => 'required',

            'referensi' => 'required',

            'pertanyaan' => 'required',

            'id_penerapan_standar' =>
                'required|exists:penerapan_standar,id'

        ]);

        $data = PertanyaanAmi::with([
             'penerapanStandar.standarMutuPeriodeAmi'
        ])->findOrFail($pertanyaan);


        $data->update([

            'indikator' =>
                $request->indikator,

            'referensi' =>
                $request->referensi,

            'pertanyaan' =>
                $request->pertanyaan,

            'id_penerapan_standar' =>
                $request->id_penerapan_standar

        ]);

        return redirect()
            ->route(
                'auditor.pertanyaan.index',
                $data->penerapanStandar
                     ->standarMutuPeriodeAmi
                     ->id_periode_ami
            )
            ->with(
                'success',
                'Pertanyaan AMI berhasil diperbarui.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

   public function destroy($pertanyaan)
{
    $data = PertanyaanAmi::with([
        'penerapanStandar.standarMutuPeriodeAmi'
    ])->findOrFail($pertanyaan);

    $idPeriode = $data->penerapanStandar
        ->standarMutuPeriodeAmi
        ->id_periode_ami;

    $data->delete();

    return redirect()
        ->route(
            'auditor.pertanyaan.index',
            $idPeriode
        )
        ->with(
            'success',
            'Pertanyaan AMI berhasil dihapus.'
        );
    }
}