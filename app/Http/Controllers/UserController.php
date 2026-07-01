<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UnitKerja;


class UserController extends Controller
{
    public function index()
    {
        $data = User::with('unit')->get();
        return view('users.index', compact('data'));
    }

    public function create()
    {
        $unit = UnitKerja::all();
        return view('users.create', compact('unit'));
    }

    public function store(Request $request)
    {
        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'id_unit_kerja' => $request->id_unit_kerja,
            'status' => $request->status
        ]);

        return redirect()->route('user.index');
    }

    public function edit($id)
    {
        $data = User::findOrFail($id);
        $unit = UnitKerja::all();

        return view('users.edit', compact('data', 'unit'));
    }

    public function update(Request $request, $id)
    {
        $data = User::findOrFail($id);

$data->update([

    'nama' => $request->nama,

    'email' => $request->email,

    'role' => $request->role,

    'id_unit_kerja' => $request->id_unit_kerja,

    'status' => $request->status

]);

if($request->password){

    $data->update([
        'password' => bcrypt($request->password)
    ]);
}

return redirect()
        ->route('user.index');
}

    public function destroy($id)
    {
        User::destroy($id);
        return redirect()->route('user.index');
    }
}