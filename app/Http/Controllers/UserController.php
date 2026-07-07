<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UnitKerja;

class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $data = User::with('unit')->get();

        return view(
            'users.index',
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
        $unit = UnitKerja::all();

        return view(
            'users.create',
            compact('unit')
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

            'nama' => 'required|string|max:100',

            'email' => 'required|email|unique:user,email',

            'password' => 'required|min:6',

            'role' => 'required',

            'id_unit_kerja' => 'required|exists:unit_kerja,id',

            'status' => 'required'

        ]);

        User::create([

            'nama' => $request->nama,

            'email' => $request->email,

            'password' => bcrypt($request->password),

            'role' => $request->role,

            'id_unit_kerja' => $request->id_unit_kerja,

            'status' => $request->status

        ]);

        return redirect()
            ->route('user.index')
            ->with(
                'success',
                'User berhasil ditambahkan.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $data = User::findOrFail($id);

        $unit = UnitKerja::all();

        return view(
            'users.edit',
            compact(
                'data',
                'unit'
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

            'nama' => 'required|string|max:100',

            'email' => 'required|email|unique:user,email,' . $id,

            'role' => 'required',

            'id_unit_kerja' => 'required|exists:unit_kerja,id',

            'status' => 'required'

        ]);

        $data = User::findOrFail($id);

        $data->update([

            'nama' => $request->nama,

            'email' => $request->email,

            'role' => $request->role,

            'id_unit_kerja' => $request->id_unit_kerja,

            'status' => $request->status

        ]);

        if (!empty($request->password)) {

            $data->update([

                'password' => bcrypt($request->password)

            ]);

        }

        return redirect()
            ->route('user.index')
            ->with(
                'success',
                'User berhasil diperbarui.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        User::destroy($id);

        return redirect()
            ->route('user.index')
            ->with(
                'success',
                'User berhasil dihapus.'
            );
    }
}