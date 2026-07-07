<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PeriodeAmi;
use App\Models\PertanyaanAmi;
use App\Models\PenerapanStandar;

class PertanyaanAmiController extends Controller
{

// =========================
// INDEX
// =========================
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
        'pertanyaan.index',
        compact(
            'data',
            'periode'
        )
    );
}

// =========================
// CREATE
// =========================
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
        'pertanyaan.create',
        compact(
            'periode',
            'penerapan'
        )
    );
}

// =========================
// STORE
// =========================
public function store(Request $request)
{
    $request->validate([

        'indikator' => 'required|string',

        'referensi' => 'required|string',

        'pertanyaan' => 'required|string',

        'id_penerapan_standar' => 'required|exists:penerapan_standar,id',

        'id_periode' => 'required|exists:periode_ami,id'

    ]);

    $cek = PenerapanStandar::where(
        'id',
        $request->id_penerapan_standar
    )
    ->whereHas(
        'standarMutuPeriodeAmi',
        function ($q) use ($request) {

            $q->where(
                'id_periode_ami',
                $request->id_periode
            );

        }
    )
    ->exists();

    if (!$cek) {

        return back()
            ->withErrors([
                'id_penerapan_standar' =>
                    'Penerapan Standar tidak sesuai dengan Periode AMI.'
            ])
            ->withInput();

    }

    $user = session('user');

    $idUser = is_array($user)
        ? $user['id']
        : $user->id;

    PertanyaanAmi::create([

        'indikator' => $request->indikator,

        'referensi' => $request->referensi,

        'pertanyaan' => $request->pertanyaan,

        'id_penerapan_standar' => $request->id_penerapan_standar,

        'id_user' => $idUser

    ]);

    return redirect()
        ->route(
            'pertanyaan.index',
            $request->id_periode
        )
        ->with(
            'success',
            'Pertanyaan berhasil ditambahkan.'
        );
}

// =========================
// EDIT
// =========================
public function edit($id)
{
    $data = PertanyaanAmi::with([
        'penerapanStandar',
        'penerapanStandar.standarMutuPeriodeAmi'
    ])->findOrFail($id);

    $idPeriode = $data->penerapanStandar
        ->standarMutuPeriodeAmi
        ->id_periode_ami;

    $penerapanStandar = PenerapanStandar::with([
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
        'pertanyaan.edit',
        compact(
            'data',
            'penerapanStandar'
        )
    );
}
    // =========================
// UPDATE
// =========================
public function update(
    Request $request,
    $id
)
{
    $request->validate([

        'indikator' => 'required|string',

        'referensi' => 'required|string',

        'pertanyaan' => 'required|string',

        'id_penerapan_standar' =>
            'required|exists:penerapan_standar,id'

    ]);

    $data = PertanyaanAmi::findOrFail($id);

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
        ->back()
        ->with(
            'success',
            'Pertanyaan berhasil diperbarui.'
        );
}

    // =========================
// DELETE
// =========================
public function destroy($id)
{
    $data = PertanyaanAmi::findOrFail($id);

    $periode = $data
        ->penerapanStandar
        ->standarMutuPeriodeAmi
        ->id_periode_ami;

    $data->delete();

    return redirect()
        ->route(
            'pertanyaan.index',
            $periode
        )
        ->with(
            'success',
            'Pertanyaan berhasil dihapus.'
        );
    }
}