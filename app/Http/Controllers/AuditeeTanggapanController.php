<?php

namespace App\Http\Controllers;

use App\Models\TemuanAmi;
use App\Models\TanggapanAuditee;
use Illuminate\Http\Request;

class AuditeeTanggapanController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DAFTAR TEMUAN
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $temuan = TemuanAmi::with([

            'pertanyaan',

            'tanggapan.user'

        ])
        ->orderBy('id','desc')
        ->get();

        return view(
            'auditee.temuan.index',
            compact('temuan')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL TEMUAN
    |--------------------------------------------------------------------------
    */
    public function show($id)
{
    $temuan = TemuanAmi::with([
        'pertanyaan',
        'tanggapan.user'
    ])->findOrFail($id);

    return view(
        'auditee.temuan.show',
        compact('temuan')
    );
}

    /*
    |--------------------------------------------------------------------------
    | FORM TANGGAPAN
    |--------------------------------------------------------------------------
    */

    public function create($id)
    {
        $temuan = TemuanAmi::with([

            'pertanyaan',

            'tanggapan'

        ])->findOrFail($id);

        return view(
            'auditee.tanggapan.create',
            compact('temuan')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN
    |--------------------------------------------------------------------------
    */

    public function store(Request $request,$id)
    {
        $request->validate([

            'tanggapan'=>'required'

        ]);

        $user=session('user');

        $idUser=is_array($user)

            ? $user['id']

            : $user->id;

        TanggapanAuditee::create([

            'id_temuan_ami'=>$id,

            'tanggapan'=>$request->tanggapan,

            'id_user'=>$idUser

        ]);

        return redirect()

            ->route(
                'auditee.temuan.index'
            )

            ->with(
                'success',
                'Tanggapan berhasil disimpan.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $data=TanggapanAuditee::with([

            'temuan'

        ])->findOrFail($id);

        return view(
            'auditee.tanggapan.edit',
            compact('data')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request,$id)
    {
        $request->validate([

            'tanggapan'=>'required'

        ]);

        $data=TanggapanAuditee::findOrFail($id);

        $data->update([

            'tanggapan'=>$request->tanggapan

        ]);

        return redirect()

            ->route(
                'auditee.temuan.index'
            )

            ->with(
                'success',
                'Tanggapan berhasil diperbarui.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $data=TanggapanAuditee::findOrFail($id);

        $data->delete();

        return redirect()

            ->route(
                'auditee.temuan.index'
            )

            ->with(
                'success',
                'Tanggapan berhasil dihapus.'
            );
    }
}