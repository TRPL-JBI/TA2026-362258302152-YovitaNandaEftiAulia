<?php

namespace App\Http\Controllers;

use App\Models\KesimpulanAudit;
use App\Models\PeriodeAmi;
use Illuminate\Http\Request;

class KesimpulanAuditorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $data = KesimpulanAudit::with([

            'periodeAmi',

            'user'

        ])
        ->orderBy('id','desc')
        ->get();

        return view(
            'auditor.kesimpulan.index',
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
        $periode = PeriodeAmi::orderBy(
            'id',
            'desc'
        )->get();

        return view(
            'auditor.kesimpulan.create',
            compact('periode')
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

            'id_periode_ami' => 'required|exists:periode_ami,id',

            'kesimpulan' => 'required'

        ]);

        $user = session('user');

        $idUser = is_array($user)
            ? $user['id']
            : $user->id;

        KesimpulanAudit::create([

            'id_periode_ami' => $request->id_periode_ami,

            'kesimpulan' => $request->kesimpulan,

            'id_user' => $idUser

        ]);

        return redirect()

            ->route('auditor.kesimpulan.index')

            ->with(
                'success',
                'Kesimpulan Audit berhasil ditambahkan.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $data = KesimpulanAudit::with([

            'periodeAmi',

            'user'

        ])->findOrFail($id);

        return view(
            'auditor.kesimpulan.show',
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
        $data = KesimpulanAudit::findOrFail($id);

        $periode = PeriodeAmi::orderBy(
            'id',
            'desc'
        )->get();

        return view(
            'auditor.kesimpulan.edit',
            compact(
                'data',
                'periode'
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

            'id_periode_ami' => 'required|exists:periode_ami,id',

            'kesimpulan' => 'required'

        ]);

        $data = KesimpulanAudit::findOrFail($id);

        $data->update([

            'id_periode_ami' => $request->id_periode_ami,

            'kesimpulan' => $request->kesimpulan

        ]);

        return redirect()

            ->route('auditor.kesimpulan.index')

            ->with(
                'success',
                'Kesimpulan Audit berhasil diperbarui.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $data = KesimpulanAudit::findOrFail($id);

        $data->delete();

        return redirect()

            ->route('auditor.kesimpulan.index')

            ->with(
                'success',
                'Kesimpulan Audit berhasil dihapus.'
            );
    }
}