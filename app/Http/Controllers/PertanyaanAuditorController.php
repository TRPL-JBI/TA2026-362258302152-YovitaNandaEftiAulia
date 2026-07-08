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

            'penerapan',

            'penerapan.standarmutuPeriode',

            'penerapan.standarmutuPeriode.standarMutu'

        ])
        ->whereHas(
            'penerapan.standarmutuPeriode',
            function ($q) use ($id) {

                $q->where(
                    'id_periode_ami',
                    $id
                );

            }
        )
        ->orderBy('id','desc')
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

            'standarmutuPeriode',

            'standarmutuPeriode.standarMutu'

        ])
        ->whereHas(
            'standarmutuPeriode',
            function ($q) use ($id) {

                $q->where(
                    'id_periode_ami',
                    $id
                );

            }
        )
        ->orderBy('id','desc')
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

            'penerapan',

            'penerapan.standarmutuPeriode',

            'penerapan.standarmutuPeriode.standarMutu',

            'penerapan.standarmutuPeriode.periodeAmi'

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

            'penerapan',

            'penerapan.standarmutuPeriode'

        ])->findOrFail($pertanyaan);

        $idPeriode = $data->penerapan
            ->standarmutuPeriode
            ->id_periode_ami;

        $penerapan = PenerapanStandar::with([

            'standarmutuPeriode',

            'standarmutuPeriode.standarMutu'

        ])
        ->whereHas(
            'standarmutuPeriode',
            function ($q) use ($idPeriode) {

                $q->where(
                    'id_periode_ami',
                    $idPeriode
                );

            }
        )
        ->orderBy('id','desc')
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

            'penerapan',

            'penerapan.standarmutuPeriode'

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
                $data->penerapan
                     ->standarmutuPeriode
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

            'penerapan',

            'penerapan.standarmutuPeriode'

        ])->findOrFail($pertanyaan);

        $idPeriode = $data->penerapan
            ->standarmutuPeriode
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

