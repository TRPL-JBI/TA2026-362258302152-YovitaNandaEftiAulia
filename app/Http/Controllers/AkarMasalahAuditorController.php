<?php

namespace App\Http\Controllers;

use App\Models\AkarMasalah;
use App\Models\TemuanAmi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Carbon\Carbon;

class AkarMasalahAuditorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    |
    | Menampilkan akar masalah yang berasal dari periode AMI
    | tempat auditor login ditugaskan.
    |
    */

    public function index(): View
    {
        $auditorId = $this->getAuditorId();

        $temuanIds = $this->getTemuanIdsAuditor(
            $auditorId
        );

        $data = AkarMasalah::with([
            'temuan',
            'user',
        ])
            ->whereIn(
                'id_temuan',
                $temuanIds
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
    | CREATE
    |--------------------------------------------------------------------------
    |
    | Menampilkan form tambah akar masalah.
    |
    */

    public function create(): View
    {
        $auditorId = $this->getAuditorId();

        $temuanIds = $this->getTemuanIdsAuditor(
            $auditorId
        );

        $temuan = TemuanAmi::whereIn(
            'id',
            $temuanIds
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
    | STORE
    |--------------------------------------------------------------------------
    |
    | Menyimpan akar masalah baru.
    |
    */

    public function store(
        Request $request
    ): RedirectResponse {
        $auditorId = $this->getAuditorId();

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
                    'Temuan audit wajib dipilih.',

                'id_temuan.integer' =>
                    'Data temuan audit tidak valid.',

                'id_temuan.exists' =>
                    'Data temuan audit tidak ditemukan.',

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
        | VALIDASI TEMUAN SESUAI PENUGASAN
        |--------------------------------------------------------------------------
        */

        $temuanIds = $this->getTemuanIdsAuditor(
            $auditorId
        );

        $temuan = TemuanAmi::whereIn(
            'id',
            $temuanIds
        )->findOrFail(
            $validated['id_temuan']
        );


        /*
        |--------------------------------------------------------------------------
        | CEK PERIODE AMI
        |--------------------------------------------------------------------------
        |
        | Auditor hanya boleh menambahkan akar masalah
        | jika periode AMI masih terbuka.
        |
        */

        $this->ensurePeriodeAmiTerbuka(
            (int) $temuan->id
        );


        /*
        |--------------------------------------------------------------------------
        | SIMPAN AKAR MASALAH
        |--------------------------------------------------------------------------
        */

        AkarMasalah::create([
            'id_temuan' =>
                $temuan->id,

            'akar_masalah' =>
                $validated['akar_masalah'],

            'id_user' =>
                $auditorId,
        ]);


        return redirect()
            ->route(
                'auditor.akarmasalah.index'
            )
            ->with(
                'success',
                'Akar masalah berhasil ditambahkan.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show($id): View
    {
        $data = $this->findAkarMasalahAuditor(
            $id
        );

        $data->load([
            'temuan',
            'user',
        ]);

        return view(
            'auditor.akarmasalah.show',
            compact('data')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    |
    | Form edit tidak dapat dibuka jika periode AMI
    | sudah ditutup.
    |
    */

    public function edit($id): View
    {
        $data = $this->findAkarMasalahAuditor(
            $id
        );


        /*
        |--------------------------------------------------------------------------
        | CEK PERIODE AMI
        |--------------------------------------------------------------------------
        */

        $this->ensurePeriodeAmiTerbuka(
            (int) $data->id_temuan
        );


        $data->load([
            'temuan',
            'user',
        ]);

        return view(
            'auditor.akarmasalah.edit',
            compact('data')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    |
    | Data tidak dapat diperbarui jika periode AMI
    | sudah ditutup.
    |
    */

    public function update(
        Request $request,
        $id
    ): RedirectResponse {

        $validated = $request->validate(
            [
                'akar_masalah' => [
                    'required',
                    'string',
                    'max:10000',
                ],
            ],
            [
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
        | VALIDASI DATA SESUAI PENUGASAN AUDITOR
        |--------------------------------------------------------------------------
        */

        $data = $this->findAkarMasalahAuditor(
            $id
        );


        /*
        |--------------------------------------------------------------------------
        | CEK PERIODE AMI
        |--------------------------------------------------------------------------
        */

        $this->ensurePeriodeAmiTerbuka(
            (int) $data->id_temuan
        );


        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        $data->update([
            'akar_masalah' =>
                $validated['akar_masalah'],
        ]);


        return redirect()
            ->route(
                'auditor.akarmasalah.index'
            )
            ->with(
                'success',
                'Akar masalah berhasil diperbarui.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    |
    | Data tidak dapat dihapus jika periode AMI
    | sudah ditutup.
    |
    */

    public function destroy(
        $id
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | VALIDASI DATA SESUAI PENUGASAN AUDITOR
        |--------------------------------------------------------------------------
        */

        $data = $this->findAkarMasalahAuditor(
            $id
        );


        /*
        |--------------------------------------------------------------------------
        | CEK PERIODE AMI
        |--------------------------------------------------------------------------
        */

        $this->ensurePeriodeAmiTerbuka(
            (int) $data->id_temuan
        );


        /*
        |--------------------------------------------------------------------------
        | HAPUS
        |--------------------------------------------------------------------------
        */

        $data->delete();


        return redirect()
            ->route(
                'auditor.akarmasalah.index'
            )
            ->with(
                'success',
                'Akar masalah berhasil dihapus.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | CEK PERIODE AMI MASIH TERBUKA
    |--------------------------------------------------------------------------
    |
    | Digunakan untuk mencegah:
    | - tambah akar masalah
    | - edit akar masalah
    | - update akar masalah
    | - hapus akar masalah
    |
    */

    private function ensurePeriodeAmiTerbuka(
        int $temuanId
    ): void {

        $periode = DB::table(
            'temuan_ami as temuan'
        )
            ->join(
                'penerapan_standar as penerapan',
                'penerapan.id',
                '=',
                'temuan.id_penerapan_standar'
            )
            ->join(
                'standarmutu_periodeami as standar_periode',
                'standar_periode.id',
                '=',
                'penerapan.id_standarmutu_periodeami'
            )
            ->join(
                'periode_ami as periode',
                'periode.id',
                '=',
                'standar_periode.id_periode_ami'
            )
            ->where(
                'temuan.id',
                $temuanId
            )
            ->select([
                'periode.id',
                'periode.status',
                'periode.tanggal_buka_ami',
                'periode.tanggal_tutup_ami',
            ])
            ->first();


        /*
        |--------------------------------------------------------------------------
        | PERIODE TIDAK DITEMUKAN
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $periode,
            404,
            'Periode AMI tidak ditemukan.'
        );


        /*
        |--------------------------------------------------------------------------
        | CEK STATUS PERIODE
        |--------------------------------------------------------------------------
        */

        $status = strtolower(
            trim(
                (string) $periode->status
            )
        );


        /*
        |--------------------------------------------------------------------------
        | CEK APAKAH PERIODE SUDAH DITUTUP
        |--------------------------------------------------------------------------
        */

        $sudahDitutup = false;


        /*
        | Jika status secara eksplisit menunjukkan
        | periode sudah ditutup.
        */

        if (
            in_array(
                $status,
                [
                    'ditutup',
                    'closed',
                    'selesai',
                ],
                true
            )
        ) {
            $sudahDitutup = true;
        }


        /*
        | Jika terdapat tanggal tutup dan
        | tanggal tersebut sudah terlewati.
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

            if (
                now()->greaterThan(
                    $tanggalTutup
                )
            ) {
                $sudahDitutup = true;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | TOLAK PERUBAHAN
        |--------------------------------------------------------------------------
        */

        abort_if(
            $sudahDitutup,
            403,
            'Periode AMI sudah ditutup. Data akar masalah tidak dapat diubah.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | MENCARI AKAR MASALAH SESUAI PENUGASAN AUDITOR
    |--------------------------------------------------------------------------
    */

    private function findAkarMasalahAuditor(
        $id
    ): AkarMasalah {

        $auditorId = $this->getAuditorId();

        $temuanIds = $this->getTemuanIdsAuditor(
            $auditorId
        );

        return AkarMasalah::whereIn(
            'id_temuan',
            $temuanIds
        )->findOrFail(
            $id
        );
    }


    /*
    |--------------------------------------------------------------------------
    | MENGAMBIL ID TEMUAN SESUAI PENUGASAN AUDITOR
    |--------------------------------------------------------------------------
    */

    private function getTemuanIdsAuditor(
        int $auditorId
    ): Collection {

        return DB::table(
            'temuan_ami as temuan'
        )
            ->join(
                'penerapan_standar as penerapan',
                'penerapan.id',
                '=',
                'temuan.id_penerapan_standar'
            )
            ->join(
                'standarmutu_periodeami as standar_periode',
                'standar_periode.id',
                '=',
                'penerapan.id_standarmutu_periodeami'
            )
            ->join(
                'tim_ami as tim',
                'tim.id_periode_ami',
                '=',
                'standar_periode.id_periode_ami'
            )
            ->where(
                'tim.id_user',
                $auditorId
            )
            ->select(
                'temuan.id'
            )
            ->distinct()
            ->pluck(
                'temuan.id'
            );
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