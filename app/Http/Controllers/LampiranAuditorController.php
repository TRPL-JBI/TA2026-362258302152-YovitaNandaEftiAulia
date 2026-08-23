<?php

namespace App\Http\Controllers;

use App\Models\LampiranAudit;
use App\Models\PeriodeAmi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Carbon\Carbon;

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
    |
    | Menyimpan lampiran baru.
    |
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
        | CEK PERIODE MASIH TERBUKA
        |--------------------------------------------------------------------------
        */

        $this->ensurePeriodeAmiTerbuka(
            (int) $periode->id
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
    |
    | Form edit hanya dapat dibuka jika periode AMI
    | masih terbuka.
    |
    | Periode lampiran dibuat tetap pada periode asal.
    | Lampiran tidak dapat dipindahkan ke periode lain.
    |
    */

    public function edit($id): View
    {
        $data = $this->findLampiranAuditor(
            $id
        );


        /*
        |--------------------------------------------------------------------------
        | CEK PERIODE LAMPIRAN
        |--------------------------------------------------------------------------
        */

        $this->ensurePeriodeAmiTerbuka(
            (int) $data->id_periode_ami
        );


        /*
        |--------------------------------------------------------------------------
        | PERIODE LAMPIRAN
        |--------------------------------------------------------------------------
        |
        | Hanya tampilkan periode asli lampiran.
        | Ini mencegah UI memberikan pilihan untuk
        | memindahkan lampiran ke periode lain.
        |
        */

        $periode = PeriodeAmi::with([
            'standarMutu',
            'unitKerja',
        ])
            ->where(
                'id',
                $data->id_periode_ami
            )
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
    |
    | Memperbarui isi/link lampiran.
    |
    | PENTING:
    | id_periode_ami TIDAK BOLEH diubah.
    |
    | Lampiran yang sudah menjadi bukti suatu periode
    | harus tetap melekat pada periode tersebut.
    |
    */

    public function update(
        Request $request,
        $id
    ): RedirectResponse {
        /*
        |--------------------------------------------------------------------------
        | VALIDASI LAMPIRAN LAMA
        |--------------------------------------------------------------------------
        |
        | Lampiran dipastikan berasal dari periode
        | penugasan auditor.
        |
        */

        $data = $this->findLampiranAuditor(
            $id
        );


        /*
        |--------------------------------------------------------------------------
        | CEK PERIODE ASAL
        |--------------------------------------------------------------------------
        |
        | Lampiran yang berada pada periode yang sudah
        | ditutup tidak boleh diubah.
        |
        */

        $this->ensurePeriodeAmiTerbuka(
            (int) $data->id_periode_ami
        );


        /*
        |--------------------------------------------------------------------------
        | VALIDASI UPDATE
        |--------------------------------------------------------------------------
        |
        | id_periode_ami SENGAJA tidak dimasukkan
        | ke dalam data yang dapat diperbarui.
        |
        | Dengan demikian walaupun request mencoba
        | mengirim:
        |
        | id_periode_ami = periode lain
        |
        | nilai tersebut tidak akan pernah digunakan.
        |
        */

        $validated = $request->validate(
            [
                'link_file' => [
                    'required',
                    'string',
                    'max:2048',
                ],
            ],
            [
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
        | PERBARUI LAMPIRAN
        |--------------------------------------------------------------------------
        |
        | id_periode_ami TIDAK DIUBAH.
        |
        | Periode tetap menggunakan:
        |
        | $data->id_periode_ami
        |
        */

        $data->update([
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
    |
    | Menghapus lampiran.
    |
    */

    public function destroy(
        $id
    ): RedirectResponse {
        /*
        |--------------------------------------------------------------------------
        | Lampiran dicari dengan filter penugasan terlebih dahulu.
        | Auditor tidak dapat menghapus lampiran periode lain.
        |--------------------------------------------------------------------------
        */

        $data = $this->findLampiranAuditor(
            $id
        );


        /*
        |--------------------------------------------------------------------------
        | CEK PERIODE
        |--------------------------------------------------------------------------
        |
        | Lampiran tidak dapat dihapus setelah periode AMI ditutup.
        |
        */

        $this->ensurePeriodeAmiTerbuka(
            (int) $data->id_periode_ami
        );


        /*
        |--------------------------------------------------------------------------
        | HAPUS LAMPIRAN
        |--------------------------------------------------------------------------
        */

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
    | MEMASTIKAN PERIODE AMI MASIH TERBUKA
    |--------------------------------------------------------------------------
    |
    | Digunakan untuk mencegah:
    |
    | - tambah lampiran
    | - edit lampiran
    | - update lampiran
    | - hapus lampiran
    |
    | setelah periode AMI ditutup.
    |
    */

    private function ensurePeriodeAmiTerbuka(
        int $periodeId
    ): void {
        $periode = PeriodeAmi::findOrFail(
            $periodeId
        );


        /*
        |--------------------------------------------------------------------------
        | CEK STATUS
        |--------------------------------------------------------------------------
        */

        $status = strtolower(
            trim(
                (string) $periode->status
            )
        );


        /*
        |--------------------------------------------------------------------------
        | STATUS PERIODE DITUTUP
        |--------------------------------------------------------------------------
        */

        $sudahDitutup = in_array(
            $status,
            [
                'ditutup',
                'closed',
                'selesai',
            ],
            true
        );


        /*
        |--------------------------------------------------------------------------
        | CEK TANGGAL TUTUP
        |--------------------------------------------------------------------------
        |
        | Jika tanggal tutup sudah dilewati,
        | periode dianggap sudah ditutup.
        |
        */

        if (
            !$sudahDitutup
            &&
            !empty(
                $periode->tanggal_tutup_ami
            )
        ) {
            $tanggalTutup = Carbon::parse(
                $periode->tanggal_tutup_ami
            )->endOfDay();

            $sudahDitutup = now()->greaterThan(
                $tanggalTutup
            );
        }


        /*
        |--------------------------------------------------------------------------
        | TOLAK PERUBAHAN
        |--------------------------------------------------------------------------
        */

        abort_if(
            $sudahDitutup,
            403,
            'Periode AMI sudah ditutup. Data lampiran tidak dapat diubah.'
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
        )->findOrFail(
            $id
        );
    }


    /*
    |--------------------------------------------------------------------------
    | MENGAMBIL PERIODE PENUGASAN AUDITOR
    |--------------------------------------------------------------------------
    */

    private function getPeriodeIdsAuditor(
        int $auditorId
    ): Collection {
        return DB::table(
            'tim_ami'
        )
            ->where(
                'id_user',
                $auditorId
            )
            ->pluck(
                'id_periode_ami'
            )
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
        $auditorId = session(
            'user_id'
        );


        if (!$auditorId) {
            $user =
                request()
                    ->attributes
                    ->get(
                        'auth_user'
                    )
                ??
                \App\Models\User::find(
                    session(
                        'user_id'
                    )
                );


            abort_unless(
                $user
                &&
                $user->status === 'aktif',
                403,
                'Akun tidak ditemukan atau sudah dinonaktifkan.'
            );


            $auditorId = is_array(
                $user
            )
                ? (
                    $user['id']
                    ?? null
                )
                : (
                    $user->id
                    ?? null
                );
        }


        abort_if(
            !$auditorId,
            401,
            'Sesi auditor tidak ditemukan. Silakan login kembali.'
        );


        return (int) $auditorId;
    }
}