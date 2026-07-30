<?php

namespace App\Http\Controllers;

use App\Models\AkarMasalah;
use App\Models\TemuanAmi;
use Illuminate\Http\Request;

class AkarMasalahAuditorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DAFTAR AKAR MASALAH
    |--------------------------------------------------------------------------
    |
    | Auditor hanya dapat melihat akar masalah dari periode AMI tempat
    | dirinya terdaftar dalam tim_ami.
    |
    */

    public function index()
    {
        $auditorId = $this->getLoginAuditorId();

        $data = AkarMasalah::with([
            'user',

            'temuan',
            'temuan.penerapanStandar',
            'temuan.penerapanStandar.user',
            'temuan.penerapanStandar.indikator',

            'temuan.penerapanStandar.standarmutuPeriode',
            'temuan.penerapanStandar.standarmutuPeriode.standarMutu',
            'temuan.penerapanStandar.standarmutuPeriode.periodeAmi',
            'temuan.penerapanStandar.standarmutuPeriode.periodeAmi.unitKerja',
            'temuan.penerapanStandar.standarmutuPeriode.periodeAmi.tim',
            'temuan.penerapanStandar.standarmutuPeriode.periodeAmi.tim.user',
        ])
            ->whereHas(
                'temuan.penerapanStandar.standarmutuPeriode.periodeAmi.tim',
                function ($query) use ($auditorId) {
                    $query->where(
                        'id_user',
                        $auditorId
                    );
                }
            )
            ->orderByDesc('id')
            ->get();

        return view(
            'auditor.akarmasalah.index',
            compact('data')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FORM TAMBAH AKAR MASALAH
    |--------------------------------------------------------------------------
    |
    | Pilihan temuan hanya berasal dari periode penugasan Auditor.
    | Temuan dari periode yang sudah ditutup tidak ditampilkan.
    |
    */

    public function create()
    {
        $auditorId = $this->getLoginAuditorId();

        $temuan = TemuanAmi::with([
            'penerapanStandar',
            'penerapanStandar.user',
            'penerapanStandar.indikator',

            'penerapanStandar.standarmutuPeriode',
            'penerapanStandar.standarmutuPeriode.standarMutu',
            'penerapanStandar.standarmutuPeriode.periodeAmi',
            'penerapanStandar.standarmutuPeriode.periodeAmi.unitKerja',
        ])
            ->whereHas(
                'penerapanStandar.standarmutuPeriode.periodeAmi.tim',
                function ($query) use ($auditorId) {
                    $query->where(
                        'id_user',
                        $auditorId
                    );
                }
            )
            ->whereHas(
                'penerapanStandar.standarmutuPeriode.periodeAmi',
                function ($query) {
                    $query->whereNotIn(
                        'status',
                        [
                            'ditutup',
                            'closed',
                            'selesai',
                        ]
                    );
                }
            )
            ->orderByDesc('id')
            ->get();

        return view(
            'auditor.akarmasalah.create',
            compact('temuan')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN AKAR MASALAH
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $auditorId = $this->getLoginAuditorId();

        $validated = $request->validate(
            [
                'id_temuan' => [
                    'required',
                    'integer',
                    'exists:temuan_ami,id',
                ],

                'akar_masalah' => [
                    'required',
                    'string',
                    'max:10000',
                ],
            ],
            [
                'id_temuan.required' =>
                    'Temuan wajib dipilih.',

                'id_temuan.integer' =>
                    'Temuan tidak valid.',

                'id_temuan.exists' =>
                    'Temuan tidak ditemukan.',

                'akar_masalah.required' =>
                    'Akar masalah wajib diisi.',

                'akar_masalah.string' =>
                    'Akar masalah harus berupa teks.',

                'akar_masalah.max' =>
                    'Akar masalah maksimal 10.000 karakter.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | VALIDASI TEMUAN DAN PENUGASAN
        |--------------------------------------------------------------------------
        |
        | Temuan harus berasal dari periode tempat Auditor ditugaskan.
        |
        */

        $temuan = $this->findAssignedTemuan(
            (int) $validated['id_temuan'],
            $auditorId
        );

        /*
        |--------------------------------------------------------------------------
        | KUNCI PERIODE YANG SUDAH DITUTUP
        |--------------------------------------------------------------------------
        */

        $this->ensurePeriodIsOpen($temuan);

        AkarMasalah::create([
            'id_temuan' => $temuan->id,

            'akar_masalah' =>
                trim($validated['akar_masalah']),

            'id_user' => $auditorId,
        ]);

        return redirect()
            ->route('auditor.akarmasalah.index')
            ->with(
                'success',
                'Akar Masalah berhasil ditambahkan.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL AKAR MASALAH
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $auditorId = $this->getLoginAuditorId();

        $data = $this->findAssignedAkarMasalah(
            (int) $id,
            $auditorId
        );

        return view(
            'auditor.akarmasalah.show',
            compact('data')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FORM EDIT AKAR MASALAH
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $auditorId = $this->getLoginAuditorId();

        $data = $this->findAssignedAkarMasalah(
            (int) $id,
            $auditorId
        );

        /*
        |--------------------------------------------------------------------------
        | DATA DIKUNCI JIKA PERIODE DITUTUP
        |--------------------------------------------------------------------------
        */

        $this->ensurePeriodIsOpen(
            $data->temuan
        );

        /*
        |--------------------------------------------------------------------------
        | PILIHAN TEMUAN YANG BOLEH DIGUNAKAN
        |--------------------------------------------------------------------------
        */

        $temuan = TemuanAmi::with([
            'penerapanStandar',
            'penerapanStandar.user',
            'penerapanStandar.indikator',

            'penerapanStandar.standarmutuPeriode',
            'penerapanStandar.standarmutuPeriode.standarMutu',
            'penerapanStandar.standarmutuPeriode.periodeAmi',
            'penerapanStandar.standarmutuPeriode.periodeAmi.unitKerja',
        ])
            ->whereHas(
                'penerapanStandar.standarmutuPeriode.periodeAmi.tim',
                function ($query) use ($auditorId) {
                    $query->where(
                        'id_user',
                        $auditorId
                    );
                }
            )
            ->whereHas(
                'penerapanStandar.standarmutuPeriode.periodeAmi',
                function ($query) {
                    $query->whereNotIn(
                        'status',
                        [
                            'ditutup',
                            'closed',
                            'selesai',
                        ]
                    );
                }
            )
            ->orderByDesc('id')
            ->get();

        return view(
            'auditor.akarmasalah.edit',
            compact(
                'data',
                'temuan'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE AKAR MASALAH
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $id
    ) {
        $auditorId = $this->getLoginAuditorId();

        $data = $this->findAssignedAkarMasalah(
            (int) $id,
            $auditorId
        );

        /*
        |--------------------------------------------------------------------------
        | PERIODE ASAL HARUS MASIH TERBUKA
        |--------------------------------------------------------------------------
        */

        $this->ensurePeriodIsOpen(
            $data->temuan
        );

        $validated = $request->validate(
            [
                'id_temuan' => [
                    'required',
                    'integer',
                    'exists:temuan_ami,id',
                ],

                'akar_masalah' => [
                    'required',
                    'string',
                    'max:10000',
                ],
            ],
            [
                'id_temuan.required' =>
                    'Temuan wajib dipilih.',

                'id_temuan.integer' =>
                    'Temuan tidak valid.',

                'id_temuan.exists' =>
                    'Temuan tidak ditemukan.',

                'akar_masalah.required' =>
                    'Akar masalah wajib diisi.',

                'akar_masalah.string' =>
                    'Akar masalah harus berupa teks.',

                'akar_masalah.max' =>
                    'Akar masalah maksimal 10.000 karakter.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | TEMUAN TUJUAN JUGA HARUS MILIK PENUGASAN AUDITOR
        |--------------------------------------------------------------------------
        */

        $temuanTujuan = $this->findAssignedTemuan(
            (int) $validated['id_temuan'],
            $auditorId
        );

        $this->ensurePeriodIsOpen(
            $temuanTujuan
        );

        $data->update([
            'id_temuan' =>
                $temuanTujuan->id,

            'akar_masalah' =>
                trim($validated['akar_masalah']),
        ]);

        return redirect()
            ->route('auditor.akarmasalah.index')
            ->with(
                'success',
                'Akar Masalah berhasil diperbarui.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | HAPUS AKAR MASALAH
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $auditorId = $this->getLoginAuditorId();

        $data = $this->findAssignedAkarMasalah(
            (int) $id,
            $auditorId
        );

        /*
        |--------------------------------------------------------------------------
        | TIDAK BOLEH DIHAPUS JIKA PERIODE SUDAH DITUTUP
        |--------------------------------------------------------------------------
        */

        $this->ensurePeriodIsOpen(
            $data->temuan
        );

        $data->delete();

        return redirect()
            ->route('auditor.akarmasalah.index')
            ->with(
                'success',
                'Akar Masalah berhasil dihapus.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | CARI AKAR MASALAH DALAM PENUGASAN AUDITOR
    |--------------------------------------------------------------------------
    */

    private function findAssignedAkarMasalah(
        int $id,
        int $auditorId
    ): AkarMasalah {
        return AkarMasalah::with([
            'user',

            'temuan',
            'temuan.penerapanStandar',
            'temuan.penerapanStandar.user',
            'temuan.penerapanStandar.indikator',

            'temuan.penerapanStandar.standarmutuPeriode',
            'temuan.penerapanStandar.standarmutuPeriode.standarMutu',
            'temuan.penerapanStandar.standarmutuPeriode.periodeAmi',
            'temuan.penerapanStandar.standarmutuPeriode.periodeAmi.unitKerja',
            'temuan.penerapanStandar.standarmutuPeriode.periodeAmi.tim',
            'temuan.penerapanStandar.standarmutuPeriode.periodeAmi.tim.user',
        ])
            ->whereHas(
                'temuan.penerapanStandar.standarmutuPeriode.periodeAmi.tim',
                function ($query) use ($auditorId) {
                    $query->where(
                        'id_user',
                        $auditorId
                    );
                }
            )
            ->findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | CARI TEMUAN DALAM PENUGASAN AUDITOR
    |--------------------------------------------------------------------------
    */

    private function findAssignedTemuan(
        int $id,
        int $auditorId
    ): TemuanAmi {
        return TemuanAmi::with([
            'penerapanStandar',
            'penerapanStandar.user',
            'penerapanStandar.indikator',

            'penerapanStandar.standarmutuPeriode',
            'penerapanStandar.standarmutuPeriode.standarMutu',
            'penerapanStandar.standarmutuPeriode.periodeAmi',
            'penerapanStandar.standarmutuPeriode.periodeAmi.unitKerja',
            'penerapanStandar.standarmutuPeriode.periodeAmi.tim',
            'penerapanStandar.standarmutuPeriode.periodeAmi.tim.user',
        ])
            ->whereHas(
                'penerapanStandar.standarmutuPeriode.periodeAmi.tim',
                function ($query) use ($auditorId) {
                    $query->where(
                        'id_user',
                        $auditorId
                    );
                }
            )
            ->findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | PASTIKAN PERIODE MASIH DAPAT DIUBAH
    |--------------------------------------------------------------------------
    */

    private function ensurePeriodIsOpen(
        TemuanAmi $temuan
    ): void {
        $periode = $temuan
            ->penerapanStandar
            ?->standarmutuPeriode
            ?->periodeAmi;

        abort_if(
            !$periode,
            404,
            'Periode AMI dari temuan tidak ditemukan.'
        );

        $status = strtolower(
            trim((string) $periode->status)
        );

        abort_if(
            in_array(
                $status,
                [
                    'ditutup',
                    'closed',
                    'selesai',
                ],
                true
            ),
            403,
            'Data tidak dapat diubah karena periode AMI sudah ditutup.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | AMBIL ID AUDITOR YANG LOGIN
    |--------------------------------------------------------------------------
    */

    private function getLoginAuditorId(): int
    {
        $user = session('user');

        abort_if(
            !$user,
            401,
            'Sesi pengguna tidak ditemukan. Silakan login kembali.'
        );

        $userId = is_array($user)
            ? ($user['id'] ?? null)
            : ($user->id ?? null);

        $role = is_array($user)
            ? ($user['role'] ?? null)
            : ($user->role ?? null);

        abort_if(
            !$userId,
            401,
            'ID pengguna pada sesi tidak ditemukan.'
        );

        abort_unless(
            $role === 'auditor',
            403,
            'Halaman ini hanya dapat diakses oleh Auditor.'
        );

        return (int) $userId;
    }
}