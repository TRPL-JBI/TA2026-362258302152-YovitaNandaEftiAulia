<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PeriodeAmi;
use App\Models\StandarMutu;
use App\Models\UnitKerja;

class PeriodeAmiController extends Controller
{
    // =========================
    // INDEX
    // =========================
    public function index()
    {
        $data = PeriodeAmi::with([
            'standarMutu',
            'unitKerja',
            'user'
        ])->get();

        return view('periode.index', compact('data'));
    }

    // =========================
    // CREATE
    // =========================
    public function create()
    {
        $standarMutu = StandarMutu::all();
        $unitKerja = UnitKerja::all();

        return view(
            'periode.create',
            compact(
                'standarMutu',
                'unitKerja'
            )
        );
    }

    // =========================
    // STORE
    // =========================
    public function store(Request $request)
    {
        $request->validate([

            'tahun' => 'required|integer|min:2025|max:2035',

            'id_standar_mutu' => 'required|exists:standar_mutu,id',

            'id_unit_kerja' => 'required|exists:unit_kerja,id',

            'tujuan_audit' => 'required|string',

            'lingkup_audit' => 'required|string',

            'waktu_audit' => 'required|string',

            'tanggal_buka_ami' => 'required|date',

            'tanggal_tutup_ami' => 'required|date|after_or_equal:tanggal_buka_ami',

            'status' => 'required|in:draft,berjalan,ditutup',

        ]);

        $user = session('user');

        $idUser = is_array($user)
            ? $user['id']
            : $user->id;

        PeriodeAmi::create([

            'tahun' => $request->tahun,

            'id_standar_mutu' => $request->id_standar_mutu,

            'id_unit_kerja' => $request->id_unit_kerja,

            'id_user' => $idUser,

            'tujuan_audit' => $request->tujuan_audit,

            'lingkup_audit' => $request->lingkup_audit,

            'waktu_audit' => $request->waktu_audit,

            'tanggal_buka_ami' => $request->tanggal_buka_ami,

            'tanggal_tutup_ami' => $request->tanggal_tutup_ami,

            'status' => $request->status

        ]);

        return redirect()
            ->route('periode-ami.index')
            ->with(
                'success',
                'Periode AMI berhasil ditambahkan.'
            );
    }

    // =========================
    // DETAIL
    // =========================
    public function show($id)
    {
        $periode = PeriodeAmi::with([
            'standarMutu',
            'unitKerja',
            'user'
        ])->findOrFail($id);

        return view(
            'periode.detail',
            compact('periode')
        );
    }

    // =========================
    // EDIT
    // =========================
    public function edit($id)
    {
        $data = PeriodeAmi::findOrFail($id);

        $standarMutu = StandarMutu::all();

        $unitKerja = UnitKerja::all();

        return view(
            'periode.edit',
            compact(
                'data',
                'standarMutu',
                'unitKerja'
            )
        );
    }

    // =========================
    // UPDATE
    // =========================
    public function update(Request $request, $id)
    {
        $request->validate([

            'tahun' => 'required|integer|min:2025|max:2035',

            'id_standar_mutu' => 'required|exists:standar_mutu,id',

            'id_unit_kerja' => 'required|exists:unit_kerja,id',

            'tujuan_audit' => 'required|string',

            'lingkup_audit' => 'required|string',

            'waktu_audit' => 'required|string',

            'tanggal_buka_ami' => 'required|date',

            'tanggal_tutup_ami' => 'required|date|after_or_equal:tanggal_buka_ami',

            'status' => 'required|in:draft,berjalan,ditutup',

        ]);

        $data = PeriodeAmi::findOrFail($id);

        $data->update([

            'tahun' => $request->tahun,

            'id_standar_mutu' => $request->id_standar_mutu,

            'id_unit_kerja' => $request->id_unit_kerja,

            'tujuan_audit' => $request->tujuan_audit,

            'lingkup_audit' => $request->lingkup_audit,

            'waktu_audit' => $request->waktu_audit,

            'tanggal_buka_ami' => $request->tanggal_buka_ami,

            'tanggal_tutup_ami' => $request->tanggal_tutup_ami,

            'status' => $request->status

        ]);

        return redirect()
            ->route('periode-ami.index')
            ->with(
                'success',
                'Periode AMI berhasil diperbarui.'
            );
    }

    // =========================
    // DELETE PAGE
    // =========================
    public function delete($id)
    {
        $data = PeriodeAmi::with([
            'standarMutu',
            'unitKerja',
            'user'
        ])->findOrFail($id);

        return view(
            'periode.delete',
            compact('data')
        );
    }

    // =========================
    // DELETE
    // =========================
    public function destroy($id)
    {
        PeriodeAmi::destroy($id);

        return redirect()
            ->route('periode-ami.index')
            ->with(
                'success',
                'Periode AMI berhasil dihapus.'
            );
    }
}