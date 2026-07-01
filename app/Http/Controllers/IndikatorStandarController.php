<?php

namespace App\Http\Controllers;

use App\Models\IsiStandarMutu;
use App\Models\IndikatorStandar;
use Illuminate\Http\Request;

class IndikatorStandarController extends Controller
{

    /**
     * Menampilkan daftar indikator
     */
    public function index($isi)
    {
        $isiStandar = IsiStandarMutu::findOrFail($isi);

        $indikator = IndikatorStandar::where(
            'id_isi_standar_mutu',
            $isi
        )->orderBy('id')->get();

        return view(
            'indikator.index',
            compact(
                'isiStandar',
                'indikator'
            )
        );
    }

    /**
     * Form tambah indikator
     */
    public function create($isi)
    {
        $isiStandar = IsiStandarMutu::findOrFail($isi);

        return view(
            'indikator.create',
            compact('isiStandar')
        );
    }

    /**
     * Simpan indikator
     */
    public function store(Request $request, $isi)
    {
        $request->validate([
            'deskripsi' => 'required'
        ]);

        IndikatorStandar::create([

            'id_isi_standar_mutu' => $isi,

            'deskripsi' => $request->deskripsi

        ]);

        return redirect()
                ->route('indikator.index', $isi)
                ->with('success', 'Indikator berhasil ditambahkan.');
    }

    /**
     * Form edit
     */
    public function edit($indikator)
    {
        $indikator = IndikatorStandar::findOrFail($indikator);

        return view(
            'indikator.edit',
            compact('indikator')
        );
    }

    /**
     * Update indikator
     */
    public function update(Request $request, $indikator)
    {
        $request->validate([
            'deskripsi' => 'required'
        ]);

        $data = IndikatorStandar::findOrFail($indikator);

        $data->update([

            'deskripsi' => $request->deskripsi

        ]);

        return redirect()
                ->route(
                    'indikator.index',
                    $data->id_isi_standar_mutu
                )
                ->with('success', 'Indikator berhasil diperbarui.');
    }

    /**
     * Hapus indikator
     */
    public function destroy($indikator)
    {
        $data = IndikatorStandar::findOrFail($indikator);

        $isi = $data->id_isi_standar_mutu;

        $data->delete();

        return redirect()
                ->route(
                    'indikator.index',
                    $isi
                )
                ->with('success', 'Indikator berhasil dihapus.');
    }

        public function show($indikator)
    {
        $indikator = IndikatorStandar::findOrFail($indikator);

        return view('indikator.show', compact('indikator'));
    }

}