<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalAmi;
use App\Models\PeriodeAmi;

class JadwalAmiController extends Controller
{
    /**
     * ==========================
     * INDEX
     * ==========================
     */
    public function index($periode)
    {
        $periodeAmi = PeriodeAmi::findOrFail($periode);

        $data = JadwalAmi::where(
            'id_periode_ami',
            $periode
        )->get();

        return view(
            'jadwal_ami.index',
            compact(
                'periodeAmi',
                'data'
            )
        );
    }

    /**
     * ==========================
     * CREATE
     * ==========================
     */
    public function create($periode)
    {
        $periodeAmi = PeriodeAmi::findOrFail($periode);

        return view(
            'jadwal_ami.create',
            compact('periodeAmi')
        );
    }

    /**
     * ==========================
     * STORE
     * ==========================
     */
    public function store(Request $request, $periode)
    {
        $request->validate([
            'kegiatan' => 'required',
            'waktu'    => 'required'
        ]);

        JadwalAmi::create([

            'id_periode_ami' => $periode,
            'kegiatan'       => $request->kegiatan,
            'waktu'          => $request->waktu

        ]);

        return redirect()
            ->route(
                'jadwal.index',
                $periode
            )
            ->with(
                'success',
                'Jadwal AMI berhasil ditambahkan.'
            );
    }

    /**
     * ==========================
     * DETAIL
     * ==========================
     */
    public function show($id)
    {
        $jadwal = JadwalAmi::findOrFail($id);

        return view(
            'jadwal_ami.show',
            compact('jadwal')
        );
    }

    /**
     * ==========================
     * EDIT
     * ==========================
     */
    public function edit($id)
    {
        $jadwal = JadwalAmi::findOrFail($id);

        return view(
            'jadwal_ami.edit',
            compact('jadwal')
        );
    }

    /**
     * ==========================
     * UPDATE
     * ==========================
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'kegiatan' => 'required',
            'waktu'    => 'required'
        ]);

        $jadwal = JadwalAmi::findOrFail($id);

        $jadwal->update([

            'kegiatan' => $request->kegiatan,
            'waktu'    => $request->waktu

        ]);

        return redirect()
            ->route(
                'jadwal.index',
                $jadwal->id_periode_ami
            )
            ->with(
                'success',
                'Jadwal berhasil diperbarui.'
            );
    }

    /**
 * ==========================
 * DELETE PAGE
 * ==========================
 */
public function delete($id)
{
    $jadwal = JadwalAmi::findOrFail($id);

    return view(
        'jadwal_ami.delete',
        compact('jadwal')
    );
}

    /**
     * ==========================
     * DELETE
     * ==========================
     */
    public function destroy($id)
    {
        $jadwal = JadwalAmi::findOrFail($id);

        $periode = $jadwal->id_periode_ami;

        $jadwal->delete();

        return redirect()
            ->route(
                'jadwal.index',
                $periode
            )
            ->with(
                'success',
                'Jadwal berhasil dihapus.'
            );
    }
}