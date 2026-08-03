<?php

namespace App\Http\Controllers;

use App\Models\LampiranAudit;
use App\Models\PeriodeAmi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LampiranAuditorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    |
    | Menampilkan lampiran yang hanya berasal dari periode AMI
    | tempat auditor login ditugaskan.
    |
    */

    public function index(): View
    {
        $auditorId = $this->getAuditorId();

        $periodeIds = $this->getPeriodeIdsAuditor(
            $auditorId
        );

        $data = LampiranAudit::whereIn(
            'id_periode_ami',
            $periodeIds
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
    | CREATE
    |--------------------------------------------------------------------------
    |
    | Daftar periode hanya berisi periode AMI
    | yang menjadi penugasan auditor.
    |
    */

    public function create(): View
    {
        $auditorId = $this->getAuditorId();

        $periodeIds = $this->getPeriodeIdsAuditor(
            $auditorId
        );

        $periode = PeriodeAmi::with([
            'standarMutu',
            'unitKerja',
        ])
            ->whereIn(
                'id',
                $periodeIds
            )
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
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request
    ): RedirectResponse {
        $auditorId = $this->getAuditorId();

        $validated = $request->validate(
            [
                'id_periode_ami' => [
                    'required',
                    'integer',
                    'exists:periode_ami,id',
                ],

                'link_file' => [
                    'required',
                    'string',
                    'max:2048',
                ],
            ],
            [
                'id_periode_ami.required' =>
                    'Periode AMI wajib dipilih.',

                'id_periode_ami.integer' =>
                    'Data periode AMI tidak valid.',

                'id_periode_ami.exists' =>
                    'Periode AMI tidak ditemukan.',

                'link_file.required' =>
                    'Link lampiran wajib diisi.',

                'link_file.string' =>
                    'Link lampiran harus berupa teks.',

                'link_file.max' =>
                    'Link lampiran maksimal 2.048 karakter.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | VALIDASI PERIODE SESUAI PENUGASAN
        |--------------------------------------------------------------------------
        */

        $periodeIds = $this->getPeriodeIdsAuditor(
            $auditorId
        );

        $periode = PeriodeAmi::whereIn(
            'id',
            $periodeIds
        )->findOrFail(
            $validated['id_periode_ami']
        );

        /*
        |--------------------------------------------------------------------------
        | SIMPAN LAMPIRAN
        |--------------------------------------------------------------------------
        */

        LampiranAudit::create([
            'id_periode_ami' =>
                $periode->id,

            'link_file' =>
                $validated['link_file'],

            'id_user' =>
                $auditorId,
        ]);

        return redirect()
            ->route(
                'auditor.lampiran.index'
            )
            ->with(
                'success',
                'Lampiran audit berhasil ditambahkan.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show($id): View
    {
        $data = $this->findLampiranAuditor(
            $id
        );

        return view(
            'auditor.lampiran.show',
            compact('data')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id): View
    {
        $auditorId = $this->getAuditorId();

        /*
        | Lampiran dipastikan berasal dari periode penugasan auditor.
        */

        $data = $this->findLampiranAuditor(
            $id
        );

        /*
        | Dropdown periode hanya menampilkan periode tugas auditor.
        */

        $periodeIds = $this->getPeriodeIdsAuditor(
            $auditorId
        );

        $periode = PeriodeAmi::with([
            'standarMutu',
            'unitKerja',
        ])
            ->whereIn(
                'id',
                $periodeIds
            )
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
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $id
    ): RedirectResponse {
        $auditorId = $this->getAuditorId();

        $validated = $request->validate(
            [
                'id_periode_ami' => [
                    'required',
                    'integer',
                    'exists:periode_ami,id',
                ],

                'link_file' => [
                    'required',
                    'string',
                    'max:2048',
                ],
            ],
            [
                'id_periode_ami.required' =>
                    'Periode AMI wajib dipilih.',

                'id_periode_ami.integer' =>
                    'Data periode AMI tidak valid.',

                'id_periode_ami.exists' =>
                    'Periode AMI tidak ditemukan.',

                'link_file.required' =>
                    'Link lampiran wajib diisi.',

                'link_file.string' =>
                    'Link lampiran harus berupa teks.',

                'link_file.max' =>
                    'Link lampiran maksimal 2.048 karakter.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | VALIDASI LAMPIRAN LAMA
        |--------------------------------------------------------------------------
        |
        | Auditor tidak dapat mengedit lampiran periode lain
        | dengan mengganti ID pada URL.
        |
        */

        $data = $this->findLampiranAuditor(
            $id
        );

        /*
        |--------------------------------------------------------------------------
        | VALIDASI PERIODE BARU
        |--------------------------------------------------------------------------
        |
        | Auditor juga tidak dapat memindahkan lampiran
        | ke periode yang bukan menjadi penugasannya.
        |
        */

        $periodeIds = $this->getPeriodeIdsAuditor(
            $auditorId
        );

        $periode = PeriodeAmi::whereIn(
            'id',
            $periodeIds
        )->findOrFail(
            $validated['id_periode_ami']
        );

        /*
        |--------------------------------------------------------------------------
        | PERBARUI LAMPIRAN
        |--------------------------------------------------------------------------
        */

        $data->update([
            'id_periode_ami' =>
                $periode->id,

            'link_file' =>
                $validated['link_file'],
        ]);

        return redirect()
            ->route(
                'auditor.lampiran.index'
            )
            ->with(
                'success',
                'Lampiran audit berhasil diperbarui.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        $id
    ): RedirectResponse {
        /*
        | Lampiran dicari dengan filter penugasan terlebih dahulu.
        | Auditor tidak dapat menghapus lampiran periode lain.
        */

        $data = $this->findLampiranAuditor(
            $id
        );

        $data->delete();

        return redirect()
            ->route(
                'auditor.lampiran.index'
            )
            ->with(
                'success',
                'Lampiran audit berhasil dihapus.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | MENCARI LAMPIRAN SESUAI PENUGASAN AUDITOR
    |--------------------------------------------------------------------------
    */

    private function findLampiranAuditor(
        $id
    ): LampiranAudit {
        $auditorId = $this->getAuditorId();

        $periodeIds = $this->getPeriodeIdsAuditor(
            $auditorId
        );

        return LampiranAudit::whereIn(
            'id_periode_ami',
            $periodeIds
        )->findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | MENGAMBIL PERIODE PENUGASAN AUDITOR
    |--------------------------------------------------------------------------
    */

    private function getPeriodeIdsAuditor(
        int $auditorId
    ): Collection {
        return DB::table('tim_ami')
            ->where(
                'id_user',
                $auditorId
            )
            ->pluck('id_periode_ami')
            ->filter()
            ->unique()
            ->values();
    }

    /*
    |--------------------------------------------------------------------------
    | MENGAMBIL ID AUDITOR LOGIN
    |--------------------------------------------------------------------------
    */

    private function getAuditorId(): int
    {
        $auditorId = session('user_id');

        if (!$auditorId) {
            $user = request()->attributes->get('auth_user')
                ?? \App\Models\User::find(session('user_id'));

            abort_unless(
                $user && $user->status === 'aktif',
                403,
                'Akun tidak ditemukan atau sudah dinonaktifkan.'
            );
            $auditorId = is_array($user)
                ? ($user['id'] ?? null)
                : ($user->id ?? null);
        }

        abort_if(
            !$auditorId,
            401,
            'Sesi auditor tidak ditemukan. Silakan login kembali.'
        );

        return (int) $auditorId;
    }
}