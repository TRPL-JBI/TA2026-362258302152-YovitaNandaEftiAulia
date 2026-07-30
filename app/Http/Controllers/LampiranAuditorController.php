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
    |
    | Auditor hanya dapat melihat lampiran dari periode AMI tempat dirinya
    | terdaftar sebagai anggota Tim AMI.
    |
    */

    public function index()
    {
        $auditorId = $this->getLoginAuditorId();

        $data = LampiranAudit::with([
            'periodeAmi',
            'periodeAmi.standarMutu',
            'periodeAmi.unitKerja',
            'periodeAmi.tim',
            'periodeAmi.tim.user',
            'user',
        ])
            ->whereHas(
                'periodeAmi.tim',
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
            'auditor.lampiran.index',
            compact('data')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FORM TAMBAH LAMPIRAN
    |--------------------------------------------------------------------------
    |
    | Daftar periode hanya menampilkan periode penugasan Auditor dan periode
    | yang masih dapat diubah.
    |
    */

    public function create()
    {
        $auditorId = $this->getLoginAuditorId();

        $periode = $this->getEditableAssignedPeriods(
            $auditorId
        );

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
        $auditorId = $this->getLoginAuditorId();

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

                'id_periode_ami.integer' =>
                    'Periode AMI tidak valid.',

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

        /*
        |--------------------------------------------------------------------------
        | VALIDASI PERIODE PENUGASAN
        |--------------------------------------------------------------------------
        |
        | Walaupun ID periode dikirim melalui form, periode tetap dicari ulang
        | berdasarkan penugasan Auditor. Manipulasi request akan menghasilkan
        | 404.
        |
        */

        $periode = $this->findAssignedPeriod(
            (int) $validated['id_periode_ami'],
            $auditorId
        );

        $this->ensurePeriodIsOpen($periode);

        LampiranAudit::create([
            'id_periode_ami' =>
                $periode->id,

            'link_file' =>
                trim($validated['link_file']),

            'id_user' =>
                $auditorId,
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
    |
    | Auditor tidak dapat membuka lampiran periode lain dengan mengganti ID.
    |
    */

    public function show($id)
    {
        $auditorId = $this->getLoginAuditorId();

        $data = $this->findAssignedLampiran(
            (int) $id,
            $auditorId
        );

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
        $auditorId = $this->getLoginAuditorId();

        $data = $this->findAssignedLampiran(
            (int) $id,
            $auditorId
        );

        /*
        |--------------------------------------------------------------------------
        | LAMPIRAN DIKUNCI JIKA PERIODE DITUTUP
        |--------------------------------------------------------------------------
        */

        $this->ensurePeriodIsOpen(
            $data->periodeAmi
        );

        $periode = $this->getEditableAssignedPeriods(
            $auditorId
        );

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

    public function update(
        Request $request,
        $id
    ) {
        $auditorId = $this->getLoginAuditorId();

        $data = $this->findAssignedLampiran(
            (int) $id,
            $auditorId
        );

        /*
        |--------------------------------------------------------------------------
        | PERIODE ASAL HARUS MASIH TERBUKA
        |--------------------------------------------------------------------------
        */

        $this->ensurePeriodIsOpen(
            $data->periodeAmi
        );

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

                'id_periode_ami.integer' =>
                    'Periode AMI tidak valid.',

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

        /*
        |--------------------------------------------------------------------------
        | PERIODE TUJUAN JUGA HARUS PENUGASAN AUDITOR
        |--------------------------------------------------------------------------
        */

        $periodeTujuan = $this->findAssignedPeriod(
            (int) $validated['id_periode_ami'],
            $auditorId
        );

        $this->ensurePeriodIsOpen(
            $periodeTujuan
        );

        $data->update([
            'id_periode_ami' =>
                $periodeTujuan->id,

            'link_file' =>
                trim($validated['link_file']),
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
        $auditorId = $this->getLoginAuditorId();

        $data = $this->findAssignedLampiran(
            (int) $id,
            $auditorId
        );

        /*
        |--------------------------------------------------------------------------
        | TIDAK BOLEH DIHAPUS JIKA PERIODE DITUTUP
        |--------------------------------------------------------------------------
        */

        $this->ensurePeriodIsOpen(
            $data->periodeAmi
        );

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
    | CARI LAMPIRAN DALAM PENUGASAN AUDITOR
    |--------------------------------------------------------------------------
    */

    private function findAssignedLampiran(
        int $id,
        int $auditorId
    ): LampiranAudit {
        return LampiranAudit::with([
            'periodeAmi',
            'periodeAmi.standarMutu',
            'periodeAmi.unitKerja',
            'periodeAmi.tim',
            'periodeAmi.tim.user',
            'user',
        ])
            ->whereHas(
                'periodeAmi.tim',
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
    | CARI PERIODE PENUGASAN AUDITOR
    |--------------------------------------------------------------------------
    */

    private function findAssignedPeriod(
        int $id,
        int $auditorId
    ): PeriodeAmi {
        return PeriodeAmi::with([
            'standarMutu',
            'unitKerja',
            'tim',
            'tim.user',
        ])
            ->whereHas(
                'tim',
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
    | DAFTAR PERIODE PENUGASAN YANG MASIH TERBUKA
    |--------------------------------------------------------------------------
    */

    private function getEditableAssignedPeriods(
        int $auditorId
    ) {
        return PeriodeAmi::with([
            'standarMutu',
            'unitKerja',
            'tim',
            'tim.user',
        ])
            ->whereHas(
                'tim',
                function ($query) use ($auditorId) {
                    $query->where(
                        'id_user',
                        $auditorId
                    );
                }
            )
            ->whereNotIn(
                'status',
                [
                    'ditutup',
                    'closed',
                    'selesai',
                ]
            )
            ->orderByDesc('tahun')
            ->orderByDesc('id')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | PASTIKAN PERIODE MASIH DAPAT DIUBAH
    |--------------------------------------------------------------------------
    */

    private function ensurePeriodIsOpen(
        ?PeriodeAmi $periode
    ): void {
        abort_if(
            !$periode,
            404,
            'Periode AMI tidak ditemukan.'
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
            'Lampiran tidak dapat diubah karena periode AMI sudah ditutup.'
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