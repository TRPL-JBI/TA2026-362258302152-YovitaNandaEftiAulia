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
    $data = PertanyaanAmi::all();

    return view(
        'pertanyaan.index',
        compact(
            'data',
            'id'
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
        'standarMutuPeriode'
    ])->get();

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
    PertanyaanAmi::create([

        'indikator' =>
            $request->indikator,

        'referensi' =>
            $request->referensi,

        'pertanyaan' =>
            $request->pertanyaan,

        'id_penerapan_standar' =>
            $request->id_penerapan_standar,

        'id_user' => is_array(session('user'))
                ? session('user')['id']
                : session('user')->id

    ]);

    return redirect()
        ->route(
            'pertanyaan.index',
            $request->id_periode
        );
}

    // =========================
    // EDIT
    // =========================
    public function edit($id)
    {
        $data = PertanyaanAmi::findOrFail($id);

        $penerapanStandar = PenerapanStandar::all();

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
        $data = PertanyaanAmi::findOrFail($id);

        $data->update([

            'pertanyaan' =>
                $request->pertanyaan,

            'id_penerapan_standar' =>
                $request->id_penerapan_standar
        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Pertanyaan berhasil diperbarui'
            );
    }

    // =========================
    // DELETE
    // =========================
    public function destroy($id)
    {
        $data = PertanyaanAmi::findOrFail($id);

        $data->delete();

        return redirect()
            ->back()
            ->with(
                'success',
                'Pertanyaan berhasil dihapus'
            );
    }
}