<?php

namespace App\Http\Controllers;

use App\Models\JadwalAmi;
use App\Models\PeriodeAmi;
use App\Traits\ChecksPeriodeAmiStatus;
use Illuminate\Http\Request;

class JadwalAmiController extends Controller
{
    use ChecksPeriodeAmiStatus;

    /**
     * ==========================
     * INDEX
     * ==========================
     *
     * Daftar jadwal tetap dapat dilihat meskipun periode AMI
     * sudah ditutup.
     */
    public function index($periode)
    {
        $periodeAmi = PeriodeAmi::findOrFail($periode);

        $data = JadwalAmi::where(
            'id_periode_ami',
            $periodeAmi->id
        )
            ->orderBy('id')
            ->get();

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
     *
     * Form tambah tidak dapat dibuka jika periode AMI
     * sudah ditutup.
     */
    public function create($periode)
    {
        $periodeAmi = PeriodeAmi::findOrFail($periode);

        $this->abortIfPeriodeClosed($periodeAmi);

        return view(
            'jadwal_ami.create',
            compact('periodeAmi')
        );
    }

    /**
     * ==========================
     * STORE
     * ==========================
     *
     * Jadwal tidak dapat ditambahkan jika periode AMI
     * sudah ditutup.
     */
    public function store(Request $request, $periode)
    {
        $periodeAmi = PeriodeAmi::findOrFail($periode);

        /*
        |--------------------------------------------------------------------------
        | CEK STATUS PERIODE SEBELUM STORE
        |--------------------------------------------------------------------------
        */

        $this->abortIfPeriodeClosed($periodeAmi);

        $validated = $request->validate(
            [
                'kegiatan' => [
                    'required',
                    'string',
                ],

                'waktu' => [
                    'required',
                    'string',
                ],
            ],
            [
                'kegiatan.required' =>
                    'Kegiatan wajib diisi.',

                'kegiatan.string' =>
                    'Kegiatan harus berupa teks.',

                'waktu.required' =>
                    'Waktu kegiatan wajib diisi.',

                'waktu.string' =>
                    'Waktu kegiatan harus berupa teks.',
            ]
        );

        JadwalAmi::create([
            'id_periode_ami' =>
                $periodeAmi->id,

            'kegiatan' =>
                $validated['kegiatan'],

            'waktu' =>
                $validated['waktu'],
        ]);

        return redirect()
            ->route(
                'jadwal.index',
                $periodeAmi->id
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
     *
     * Detail tetap dapat dilihat meskipun periode AMI
     * sudah ditutup.
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
     *
     * Form edit tidak dapat dibuka jika periode AMI
     * sudah ditutup.
     */
    public function edit($id)
    {
        $jadwal = JadwalAmi::findOrFail($id);

        $periodeAmi = PeriodeAmi::findOrFail(
            $jadwal->id_periode_ami
        );

        /*
        |--------------------------------------------------------------------------
        | CEK STATUS PERIODE SEBELUM MEMBUKA FORM EDIT
        |--------------------------------------------------------------------------
        */

        $this->abortIfPeriodeClosed($periodeAmi);

        return view(
            'jadwal_ami.edit',
            compact('jadwal')
        );
    }

    /**
     * ==========================
     * UPDATE
     * ==========================
     *
     * Jadwal tidak dapat diperbarui jika periode AMI
     * sudah ditutup.
     */
    public function update(Request $request, $id)
    {
        $jadwal = JadwalAmi::findOrFail($id);

        $periodeAmi = PeriodeAmi::findOrFail(
            $jadwal->id_periode_ami
        );

        /*
        |--------------------------------------------------------------------------
        | CEK STATUS PERIODE SEBELUM UPDATE
        |--------------------------------------------------------------------------
        */

        $this->abortIfPeriodeClosed($periodeAmi);

        $validated = $request->validate(
            [
                'kegiatan' => [
                    'required',
                    'string',
                ],

                'waktu' => [
                    'required',
                    'string',
                ],
            ],
            [
                'kegiatan.required' =>
                    'Kegiatan wajib diisi.',

                'kegiatan.string' =>
                    'Kegiatan harus berupa teks.',

                'waktu.required' =>
                    'Waktu kegiatan wajib diisi.',

                'waktu.string' =>
                    'Waktu kegiatan harus berupa teks.',
            ]
        );

        $jadwal->update([
            'kegiatan' =>
                $validated['kegiatan'],

            'waktu' =>
                $validated['waktu'],
        ]);

        return redirect()
            ->route(
                'jadwal.index',
                $periodeAmi->id
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
     *
     * Halaman konfirmasi hapus tidak dapat dibuka jika
     * periode AMI sudah ditutup.
     */
    public function delete($id)
    {
        $jadwal = JadwalAmi::findOrFail($id);

        $periodeAmi = PeriodeAmi::findOrFail(
            $jadwal->id_periode_ami
        );

        /*
        |--------------------------------------------------------------------------
        | CEK STATUS PERIODE SEBELUM MEMBUKA HALAMAN DELETE
        |--------------------------------------------------------------------------
        */

        $this->abortIfPeriodeClosed($periodeAmi);

        return view(
            'jadwal_ami.delete',
            compact('jadwal')
        );
    }

    /**
     * ==========================
     * DELETE
     * ==========================
     *
     * Jadwal tidak dapat dihapus jika periode AMI
     * sudah ditutup.
     */
    public function destroy($id)
    {
        $jadwal = JadwalAmi::findOrFail($id);

        $periodeAmi = PeriodeAmi::findOrFail(
            $jadwal->id_periode_ami
        );

        /*
        |--------------------------------------------------------------------------
        | CEK STATUS PERIODE SEBELUM DELETE
        |--------------------------------------------------------------------------
        */

        $this->abortIfPeriodeClosed($periodeAmi);

        $periodeId = $periodeAmi->id;

        $jadwal->delete();

        return redirect()
            ->route(
                'jadwal.index',
                $periodeId
            )
            ->with(
                'success',
                'Jadwal berhasil dihapus.'
            );
    }
}