<?php

namespace App\Http\Controllers;

use App\Models\PenerapanStandar;
use App\Models\TemuanAmi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TemuanAuditorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DAFTAR TEMUAN
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $data = TemuanAmi::with([
            'penerapan',
            'penerapan.indikator',
            'penerapan.user',
            'penerapan.standarmutuPeriode',
            'penerapan.standarmutuPeriode.standarMutu',
            'penerapan.standarmutuPeriode.periodeAmi',
            'penerapan.standarmutuPeriode.periodeAmi.unitKerja',
            'tanggapan.user',
            'akarMasalah',
        ])
            ->orderByDesc('id')
            ->get();

        return view(
            'auditor.temuan.index',
            compact('data')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FORM TAMBAH
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $penerapan = PenerapanStandar::with([
            'indikator',
            'user',
            'standarmutuPeriode',
            'standarmutuPeriode.standarMutu',
            'standarmutuPeriode.periodeAmi',
            'standarmutuPeriode.periodeAmi.unitKerja',
        ])
            ->whereDoesntHave('temuan')
            ->orderByDesc('id')
            ->get();

        return view(
            'auditor.temuan.create',
            compact('penerapan')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_penerapan_standar' => [
                'required',
                'integer',
                'exists:penerapan_standar,id',

                /*
                 * Satu penerapan hanya memiliki satu temuan.
                 * Hapus Rule::unique apabila satu penerapan boleh
                 * mempunyai lebih dari satu temuan.
                 */
                Rule::unique(
                    'temuan_ami',
                    'id_penerapan_standar'
                ),
            ],

            'temuan' => [
                'required',
                'string',
                'max:10000',
            ],

            'status_temuan' => [
                'required',
                Rule::in([
                    'open',
                    'closed',
                ]),
            ],
        ], [
            'id_penerapan_standar.required' =>
                'Penerapan standar wajib dipilih.',

            'id_penerapan_standar.exists' =>
                'Penerapan standar tidak ditemukan.',

            'id_penerapan_standar.unique' =>
                'Penerapan standar tersebut sudah mempunyai temuan.',

            'temuan.required' =>
                'Temuan audit wajib diisi.',

            'status_temuan.required' =>
                'Status temuan wajib dipilih.',
        ]);

        TemuanAmi::create($validated);

        return redirect()
            ->route('auditor.temuan.index')
            ->with(
                'success',
                'Temuan audit berhasil ditambahkan.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $temuan = TemuanAmi::with([
            'penerapan',
            'penerapan.indikator',
            'penerapan.user',
            'penerapan.standarmutuPeriode',
            'penerapan.standarmutuPeriode.standarMutu',
            'penerapan.standarmutuPeriode.periodeAmi',
            'penerapan.standarmutuPeriode.periodeAmi.unitKerja',
            'tanggapan.user',
            'akarMasalah',
        ])->findOrFail($id);

        return view(
            'auditor.temuan.show',
            compact('temuan')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FORM EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $temuan = TemuanAmi::findOrFail($id);

        $penerapan = PenerapanStandar::with([
            'indikator',
            'user',
            'standarmutuPeriode',
            'standarmutuPeriode.standarMutu',
            'standarmutuPeriode.periodeAmi',
            'standarmutuPeriode.periodeAmi.unitKerja',
        ])
            ->where(function ($query) use ($temuan) {
                $query
                    ->whereDoesntHave('temuan')
                    ->orWhere(
                        'id',
                        $temuan->id_penerapan_standar
                    );
            })
            ->orderByDesc('id')
            ->get();

        return view(
            'auditor.temuan.edit',
            compact(
                'temuan',
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
        $temuan = TemuanAmi::findOrFail($id);

        $validated = $request->validate([
            'id_penerapan_standar' => [
                'required',
                'integer',
                'exists:penerapan_standar,id',

                Rule::unique(
                    'temuan_ami',
                    'id_penerapan_standar'
                )->ignore($temuan->id),
            ],

            'temuan' => [
                'required',
                'string',
                'max:10000',
            ],

            'status_temuan' => [
                'required',
                Rule::in([
                    'open',
                    'closed',
                ]),
            ],
        ]);

        $temuan->update($validated);

        return redirect()
            ->route('auditor.temuan.index')
            ->with(
                'success',
                'Temuan audit berhasil diperbarui.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | HAPUS
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
                'Temuan audit berhasil dihapus.'
            );
    }
}