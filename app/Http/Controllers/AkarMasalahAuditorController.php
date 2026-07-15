<?php

namespace App\Http\Controllers;

use App\Models\AkarMasalah;
use App\Models\TemuanAmi;
use Illuminate\Http\Request;

class AkarMasalahAuditorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $data = AkarMasalah::with([

            'temuan',

            'user'

        ])
        ->orderBy('id','desc')
        ->get();

        return view(
            'auditor.akarmasalah.index',
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
        $temuan = TemuanAmi::orderBy(
            'id',
            'desc'
        )->get();

        return view(
            'auditor.akarmasalah.create',
            compact('temuan')
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

            'id_temuan'     => 'required|exists:temuan_ami,id',

            'akar_masalah'  => 'required'

        ]);

        $user = session('user');

        $idUser = is_array($user)
            ? $user['id']
            : $user->id;

        AkarMasalah::create([

            'id_temuan'    => $request->id_temuan,

            'akar_masalah' => $request->akar_masalah,

            'id_user'      => $idUser

        ]);

        return redirect()

            ->route('auditor.akarmasalah.index')

            ->with(
                'success',
                'Akar Masalah berhasil ditambahkan.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $data = AkarMasalah::with([

            'temuan',

            'user'

        ])->findOrFail($id);

        return view(
            'auditor.akarmasalah.show',
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
        $data = AkarMasalah::findOrFail($id);

        $temuan = TemuanAmi::orderBy(
            'id',
            'desc'
        )->get();

        return view(
            'auditor.akarmasalah.edit',
            compact(
                'data',
                'temuan'
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

            'id_temuan'     => 'required|exists:temuan_ami,id',

            'akar_masalah'  => 'required'

        ]);

        $data = AkarMasalah::findOrFail($id);

        $data->update([

            'id_temuan'    => $request->id_temuan,

            'akar_masalah' => $request->akar_masalah

        ]);

        return redirect()

            ->route('auditor.akarmasalah.index')

            ->with(
                'success',
                'Akar Masalah berhasil diperbarui.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $data = AkarMasalah::findOrFail($id);

        $data->delete();

        return redirect()

            ->route('auditor.akarmasalah.index')

            ->with(
                'success',
                'Akar Masalah berhasil dihapus.'
            );
    }
}