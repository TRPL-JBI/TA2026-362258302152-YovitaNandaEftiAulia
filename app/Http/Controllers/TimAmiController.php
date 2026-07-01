<?php

namespace App\Http\Controllers;

use App\Models\TimAmi;
use App\Models\User;
use App\Models\PeriodeAmi;
use Illuminate\Http\Request;

class TimAmiController extends Controller
{
    /**
     * Menampilkan daftar Tim AMI
     */
    public function index($periode)
    {
        $periodeAmi = PeriodeAmi::findOrFail($periode);

        $data = TimAmi::with('user')
            ->where('id_periode_ami', $periode)
            ->get();

        return view(
            'tim_ami.index',
            compact(
                'periodeAmi',
                'data'
            )
        );
    }

    /**
     * Form tambah
     */
    public function create($periode)
    {
        $periodeAmi = PeriodeAmi::findOrFail($periode);

        $users = User::orderBy('nama')->get();

        return view(
            'tim_ami.create',
            compact(
                'periodeAmi',
                'users'
            )
        );
    }

    /**
     * Simpan
     */
    public function store(Request $request, $periode)
    {
        $request->validate([
            'id_user' => 'required',
            'role'    => 'required'
        ]);

        TimAmi::create([
            'id_periode_ami' => $periode,
            'id_user'        => $request->id_user,
            'role'           => $request->role
        ]);

        return redirect()
            ->route('tim-ami.index', $periode)
            ->with('success', 'Tim AMI berhasil ditambahkan.');
    }

    /**
     * Detail
     */
    public function show($id)
    {
        $tim = TimAmi::with(['user', 'periode'])->findOrFail($id);

        return view('tim_ami.show', compact('tim'));
    }

    /**
     * Form edit
     */
    public function edit($id)
    {
        $tim = TimAmi::findOrFail($id);

        $users = User::orderBy('nama')->get();

        return view(
            'tim_ami.edit',
            compact(
                'tim',
                'users'
            )
        );
    }

    /**
     * Update
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_user' => 'required',
            'role'    => 'required'
        ]);

        $tim = TimAmi::findOrFail($id);

        $tim->update([
            'id_user' => $request->id_user,
            'role'    => $request->role
        ]);

        return redirect()
            ->route('tim-ami.index', $tim->id_periode_ami)
            ->with('success', 'Tim AMI berhasil diperbarui.');
    }

    /**
     * Hapus
     */
    public function destroy($id)
    {
        $tim = TimAmi::findOrFail($id);

        $periode = $tim->id_periode_ami;

        $tim->delete();

        return redirect()
            ->route('tim-ami.index', $periode)
            ->with('success', 'Tim AMI berhasil dihapus.');
    }
}