<?php

namespace App\Http\Controllers;

use App\Models\TanggapanAuditee;
use App\Models\TemuanAmi;
use Illuminate\Http\Request;

class AuditeeTanggapanController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DAFTAR TEMUAN AUDITEE
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $idUser = $this->getLoginUserId();

        $temuan = TemuanAmi::with([
            'penerapan',
            'penerapan.indikator',
            'penerapan.user',

            'penerapan.standarmutuPeriode',
            'penerapan.standarmutuPeriode.standarMutu',
            'penerapan.standarmutuPeriode.periodeAmi',
            'penerapan.standarmutuPeriode.periodeAmi.unitKerja',

            'tanggapan',
            'tanggapan.user',

            'akarMasalah',
        ])
            ->whereHas(
                'penerapan',
                function ($query) use ($idUser) {
                    $query->where('id_user', $idUser);
                }
            )
            ->orderByDesc('id')
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
        $temuan = $this->findOwnedTemuan($id);

        return view(
            'auditee.temuan.show',
            compact('temuan')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FORM TAMBAH TANGGAPAN
    |--------------------------------------------------------------------------
    */

    public function create($id)
    {
        $temuan = $this->findOwnedTemuan($id);

        if ($temuan->tanggapan->isNotEmpty()) {
            return redirect()
                ->route(
                    'auditee.temuan.show',
                    $temuan->id
                )
                ->with(
                    'error',
                    'Temuan ini sudah mempunyai tanggapan.'
                );
        }

        return view(
            'auditee.tanggapan.create',
            compact('temuan')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN TANGGAPAN
    |--------------------------------------------------------------------------
    */

    public function store(Request $request, $id)
    {
        $idUser = $this->getLoginUserId();

        $temuan = $this->findOwnedTemuan($id);

        if ($temuan->tanggapan->isNotEmpty()) {
            return redirect()
                ->route(
                    'auditee.temuan.show',
                    $temuan->id
                )
                ->with(
                    'error',
                    'Temuan ini sudah mempunyai tanggapan.'
                );
        }

        $validated = $request->validate(
            [
                'tanggapan' => [
                    'required',
                    'string',
                    'max:10000',
                ],
            ],
            [
                'tanggapan.required' =>
                    'Tanggapan wajib diisi.',

                'tanggapan.string' =>
                    'Tanggapan harus berupa teks.',

                'tanggapan.max' =>
                    'Tanggapan maksimal 10.000 karakter.',
            ]
        );

        TanggapanAuditee::create([
            'id_temuan_ami' => $temuan->id,
            'tanggapan' => $validated['tanggapan'],
            'id_user' => $idUser,
        ]);

        return redirect()
            ->route(
                'auditee.temuan.show',
                $temuan->id
            )
            ->with(
                'success',
                'Tanggapan berhasil disimpan.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | FORM EDIT TANGGAPAN
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $idUser = $this->getLoginUserId();

        $data = TanggapanAuditee::with([
            'temuan',

            'temuan.penerapan',
            'temuan.penerapan.indikator',
            'temuan.penerapan.user',

            'temuan.penerapan.standarmutuPeriode',
            'temuan.penerapan.standarmutuPeriode.standarMutu',
            'temuan.penerapan.standarmutuPeriode.periodeAmi',
            'temuan.penerapan.standarmutuPeriode.periodeAmi.unitKerja',
        ])
            ->where('id_user', $idUser)
            ->findOrFail($id);

        /*
         * Pastikan temuan memang berasal dari penerapan milik
         * auditee yang sedang login.
         */
        abort_unless(
            (int) ($data->temuan->penerapan->id_user ?? 0)
                === $idUser,
            403,
            'Anda tidak mempunyai akses ke tanggapan ini.'
        );

        return view(
            'auditee.tanggapan.edit',
            compact('data')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE TANGGAPAN
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $idUser = $this->getLoginUserId();

        $data = TanggapanAuditee::with([
            'temuan',
            'temuan.penerapan',
        ])
            ->where('id_user', $idUser)
            ->findOrFail($id);

        abort_unless(
            (int) ($data->temuan->penerapan->id_user ?? 0)
                === $idUser,
            403,
            'Anda tidak mempunyai akses ke tanggapan ini.'
        );

        $validated = $request->validate(
            [
                'tanggapan' => [
                    'required',
                    'string',
                    'max:10000',
                ],
            ],
            [
                'tanggapan.required' =>
                    'Tanggapan wajib diisi.',

                'tanggapan.string' =>
                    'Tanggapan harus berupa teks.',

                'tanggapan.max' =>
                    'Tanggapan maksimal 10.000 karakter.',
            ]
        );

        $data->update([
            'tanggapan' => $validated['tanggapan'],
        ]);

        return redirect()
            ->route(
                'auditee.temuan.show',
                $data->id_temuan_ami
            )
            ->with(
                'success',
                'Tanggapan berhasil diperbarui.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | HAPUS TANGGAPAN
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $idUser = $this->getLoginUserId();

        $data = TanggapanAuditee::with([
            'temuan',
            'temuan.penerapan',
        ])
            ->where('id_user', $idUser)
            ->findOrFail($id);

        abort_unless(
            (int) ($data->temuan->penerapan->id_user ?? 0)
                === $idUser,
            403,
            'Anda tidak mempunyai akses ke tanggapan ini.'
        );

        $idTemuan = $data->id_temuan_ami;

        $data->delete();

        return redirect()
            ->route(
                'auditee.temuan.show',
                $idTemuan
            )
            ->with(
                'success',
                'Tanggapan berhasil dihapus.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | CARI TEMUAN MILIK AUDITEE LOGIN
    |--------------------------------------------------------------------------
    */

    private function findOwnedTemuan($id): TemuanAmi
    {
        $idUser = $this->getLoginUserId();

        return TemuanAmi::with([
            /*
            |--------------------------------------------------------------------------
            | PENERAPAN
            |--------------------------------------------------------------------------
            */

            'penerapan',
            'penerapan.indikator',
            'penerapan.user',

            /*
            |--------------------------------------------------------------------------
            | PERIODE DAN STANDAR
            |--------------------------------------------------------------------------
            */

            'penerapan.standarmutuPeriode',
            'penerapan.standarmutuPeriode.standarMutu',
            'penerapan.standarmutuPeriode.periodeAmi',
            'penerapan.standarmutuPeriode.periodeAmi.unitKerja',

            /*
            |--------------------------------------------------------------------------
            | TANGGAPAN DAN AKAR MASALAH
            |--------------------------------------------------------------------------
            */

            'tanggapan',
            'tanggapan.user',
            'akarMasalah',
        ])
            ->whereHas(
                'penerapan',
                function ($query) use ($idUser) {
                    $query->where(
                        'id_user',
                        $idUser
                    );
                }
            )
            ->findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | ID USER LOGIN
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

        $idUser = is_array($user)
            ? ($user['id'] ?? null)
            : ($user->id ?? null);

        abort_if(
            !$idUser,
            401,
            'ID pengguna pada sesi tidak ditemukan.'
        );

        return (int) $idUser;
    }
}