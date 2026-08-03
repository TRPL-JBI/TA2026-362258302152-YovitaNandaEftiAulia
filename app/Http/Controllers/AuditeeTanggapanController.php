<?php

namespace App\Http\Controllers;

use App\Models\TanggapanAuditee;
use App\Models\TemuanAmi;
use App\Models\User;
use App\Traits\ChecksPeriodeAmiStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditeeTanggapanController extends Controller
{
    use ChecksPeriodeAmiStatus;

    /*
    |--------------------------------------------------------------------------
    | DAFTAR TEMUAN AUDITEE
    |--------------------------------------------------------------------------
    */

    public function index(): View
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
                    $query->where(
                        'id_user',
                        $idUser
                    );
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
    |
    | Detail tetap dapat dilihat walaupun periode sudah ditutup.
    | Periode tertutup hanya mengunci perubahan data.
    |
    */

    public function show(
        int $id
    ): View {
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
    |
    | Form tidak dapat dibuka jika periode AMI sudah ditutup.
    |
    */

    public function create(
        int $id
    ): View|RedirectResponse {
        $temuan = $this->findOwnedTemuan($id);

        /*
        |--------------------------------------------------------------------------
        | CEK STATUS PERIODE
        |--------------------------------------------------------------------------
        */

        $this->abortIfPeriodeClosed(
            $temuan
                ->penerapan
                ?->standarmutuPeriode
                ?->periodeAmi
        );

        /*
        |--------------------------------------------------------------------------
        | SATU TEMUAN HANYA MEMILIKI SATU TANGGAPAN AUDITEE
        |--------------------------------------------------------------------------
        */

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
    |
    | Tanggapan tidak dapat disimpan jika periode AMI sudah ditutup.
    |
    */

    public function store(
        Request $request,
        int $id
    ): RedirectResponse {
        $idUser = $this->getLoginUserId();

        $temuan = $this->findOwnedTemuan($id);

        /*
        |--------------------------------------------------------------------------
        | CEK STATUS PERIODE SEBELUM STORE
        |--------------------------------------------------------------------------
        */

        $this->abortIfPeriodeClosed(
            $temuan
                ->penerapan
                ?->standarmutuPeriode
                ?->periodeAmi
        );

        /*
        |--------------------------------------------------------------------------
        | CEK TANGGAPAN YANG SUDAH ADA
        |--------------------------------------------------------------------------
        */

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
            'id_temuan_ami' =>
                $temuan->id,

            'tanggapan' =>
                trim($validated['tanggapan']),

            'id_user' =>
                $idUser,
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
    |
    | Form edit tidak dapat dibuka jika periode AMI sudah ditutup.
    |
    */

    public function edit(
        int $id
    ): View {
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
            ->where(
                'id_user',
                $idUser
            )
            ->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | PASTIKAN TANGGAPAN BERASAL DARI PENERAPAN MILIK AUDITEE
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) (
                $data
                    ->temuan
                    ?->penerapan
                    ?->id_user
                ?? 0
            ) === $idUser,
            403,
            'Anda tidak mempunyai akses ke tanggapan ini.'
        );

        /*
        |--------------------------------------------------------------------------
        | CEK STATUS PERIODE SEBELUM MEMBUKA FORM EDIT
        |--------------------------------------------------------------------------
        */

        $this->abortIfPeriodeClosed(
            $data
                ->temuan
                ?->penerapan
                ?->standarmutuPeriode
                ?->periodeAmi
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
    |
    | Tanggapan tidak dapat diubah jika periode AMI sudah ditutup.
    |
    */

    public function update(
        Request $request,
        int $id
    ): RedirectResponse {
        $idUser = $this->getLoginUserId();

        $data = TanggapanAuditee::with([
            'temuan',
            'temuan.penerapan',
            'temuan.penerapan.standarmutuPeriode',
            'temuan.penerapan.standarmutuPeriode.periodeAmi',
        ])
            ->where(
                'id_user',
                $idUser
            )
            ->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | CEK KEPEMILIKAN TANGGAPAN
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) (
                $data
                    ->temuan
                    ?->penerapan
                    ?->id_user
                ?? 0
            ) === $idUser,
            403,
            'Anda tidak mempunyai akses ke tanggapan ini.'
        );

        /*
        |--------------------------------------------------------------------------
        | CEK STATUS PERIODE SEBELUM UPDATE
        |--------------------------------------------------------------------------
        */

        $this->abortIfPeriodeClosed(
            $data
                ->temuan
                ?->penerapan
                ?->standarmutuPeriode
                ?->periodeAmi
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
            'tanggapan' =>
                trim($validated['tanggapan']),
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
    |
    | Tanggapan tidak dapat dihapus jika periode AMI sudah ditutup.
    |
    */

    public function destroy(
        int $id
    ): RedirectResponse {
        $idUser = $this->getLoginUserId();

        $data = TanggapanAuditee::with([
            'temuan',
            'temuan.penerapan',
            'temuan.penerapan.standarmutuPeriode',
            'temuan.penerapan.standarmutuPeriode.periodeAmi',
        ])
            ->where(
                'id_user',
                $idUser
            )
            ->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | CEK KEPEMILIKAN TANGGAPAN
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) (
                $data
                    ->temuan
                    ?->penerapan
                    ?->id_user
                ?? 0
            ) === $idUser,
            403,
            'Anda tidak mempunyai akses ke tanggapan ini.'
        );

        /*
        |--------------------------------------------------------------------------
        | CEK STATUS PERIODE SEBELUM DELETE
        |--------------------------------------------------------------------------
        */

        $this->abortIfPeriodeClosed(
            $data
                ->temuan
                ?->penerapan
                ?->standarmutuPeriode
                ?->periodeAmi
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

    private function findOwnedTemuan(
        int $id
    ): TemuanAmi {
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
        $idUser = session('user_id');

        abort_unless(
            $idUser,
            401,
            'Sesi pengguna tidak ditemukan. Silakan login kembali.'
        );

        $user = request()
            ->attributes
            ->get('auth_user');

        if (!$user instanceof User) {
            $user = User::query()->find(
                $idUser
            );
        }

        abort_unless(
            $user,
            401,
            'Data pengguna yang sedang login tidak ditemukan.'
        );

        $status = strtolower(
            trim(
                (string) $user->status
            )
        );

        abort_unless(
            $status === 'aktif',
            403,
            'Akun tidak ditemukan atau sudah dinonaktifkan.'
        );

        return (int) $user->id;
    }
}