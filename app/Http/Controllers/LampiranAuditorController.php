<?php

namespace App\Http\Controllers;

use App\Models\LampiranAudit;
use App\Models\PeriodeAmi;
use Illuminate\Http\Request;

class LampiranAuditorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DAFTAR LAMPIRAN
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $data = LampiranAudit::with([
            'periodeAmi',
            'user',
        ])
            ->orderByDesc('id')
            ->get();

        return view(
            'auditor.lampiran.index',
            compact('data')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FORM TAMBAH LAMPIRAN
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $periode = PeriodeAmi::with([
            'standarMutu',
            'unitKerja',
        ])
            ->orderByDesc('tahun')
            ->orderByDesc('id')
            ->get();

        return view(
            'auditor.lampiran.create',
            compact('periode')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN LAMPIRAN
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'id_periode_ami' => [
                    'required',
                    'integer',
                    'exists:periode_ami,id',
                ],

                'link_file' => [
                    'required',
                    'url',
                    'max:2048',
                ],
            ],
            [
                'id_periode_ami.required' =>
                    'Periode AMI wajib dipilih.',

                'id_periode_ami.exists' =>
                    'Periode AMI yang dipilih tidak ditemukan.',

                'link_file.required' =>
                    'Link lampiran wajib diisi.',

                'link_file.url' =>
                    'Link lampiran harus berupa URL yang valid.',

                'link_file.max' =>
                    'Link lampiran maksimal 2048 karakter.',
            ]
        );

        $idUser = $this->getLoginUserId();

        LampiranAudit::create([
            'id_periode_ami' =>
                $validated['id_periode_ami'],

            'link_file' =>
                $validated['link_file'],

            'id_user' =>
                $idUser,
        ]);

        return redirect()
            ->route('auditor.lampiran.index')
            ->with(
                'success',
                'Lampiran audit berhasil ditambahkan.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL LAMPIRAN
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $data = LampiranAudit::with([
            'periodeAmi',
            'periodeAmi.standarMutu',
            'periodeAmi.unitKerja',
            'user',
        ])->findOrFail($id);

        return view(
            'auditor.lampiran.show',
            compact('data')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FORM EDIT LAMPIRAN
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $data = LampiranAudit::findOrFail($id);

        $periode = PeriodeAmi::with([
            'standarMutu',
            'unitKerja',
        ])
            ->orderByDesc('tahun')
            ->orderByDesc('id')
            ->get();

        return view(
            'auditor.lampiran.edit',
            compact(
                'data',
                'periode'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE LAMPIRAN
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $data = LampiranAudit::findOrFail($id);

        $validated = $request->validate(
            [
                'id_periode_ami' => [
                    'required',
                    'integer',
                    'exists:periode_ami,id',
                ],

                'link_file' => [
                    'required',
                    'url',
                    'max:2048',
                ],
            ],
            [
                'id_periode_ami.required' =>
                    'Periode AMI wajib dipilih.',

                'id_periode_ami.exists' =>
                    'Periode AMI yang dipilih tidak ditemukan.',

                'link_file.required' =>
                    'Link lampiran wajib diisi.',

                'link_file.url' =>
                    'Link lampiran harus berupa URL yang valid.',

                'link_file.max' =>
                    'Link lampiran maksimal 2048 karakter.',
            ]
        );

        $data->update([
            'id_periode_ami' =>
                $validated['id_periode_ami'],

            'link_file' =>
                $validated['link_file'],
        ]);

        return redirect()
            ->route('auditor.lampiran.index')
            ->with(
                'success',
                'Lampiran audit berhasil diperbarui.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | HAPUS LAMPIRAN
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $data = LampiranAudit::findOrFail($id);

        $data->delete();

        return redirect()
            ->route('auditor.lampiran.index')
            ->with(
                'success',
                'Lampiran audit berhasil dihapus.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | AMBIL ID USER LOGIN
    |--------------------------------------------------------------------------
    */

    private function getLoginUserId(): int
    {
        $user = session('user');

        abort_if(
            !$user,
            401,
            'Sesi pengguna tidak ditemukan. Silakan login kembali.'
        );

        if (is_array($user)) {
            abort_if(
                empty($user['id']),
                401,
                'ID pengguna pada sesi tidak ditemukan.'
            );

            return (int) $user['id'];
        }

        abort_if(
            empty($user->id),
            401,
            'ID pengguna pada sesi tidak ditemukan.'
        );

        return (int) $user->id;
    }
}