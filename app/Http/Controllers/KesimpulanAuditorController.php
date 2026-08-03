<?php

namespace App\Http\Controllers;

use App\Traits\ChecksPeriodeAmiStatus;
use App\Models\KesimpulanAudit;
use App\Models\PeriodeAmi;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class KesimpulanAuditorController extends Controller
{
    use ChecksPeriodeAmiStatus;
    /*
    |--------------------------------------------------------------------------
    | AMBIL ID AUDITOR YANG LOGIN
    |--------------------------------------------------------------------------
    |
    | Session hanya menyimpan user_id.
    | Data pengguna diperiksa kembali dari database.
    |
    */

    private function currentUserId(): int
    {
        $userId = session('user_id');

        if (!$userId) {
            abort(
                401,
                'Sesi pengguna tidak ditemukan. Silakan login kembali.'
            );
        }

        $user = request()->attributes->get('auth_user');

        if (!$user instanceof User) {
            $user = User::query()->find($userId);
        }

        abort_unless(
            $user,
            401,
            'Data pengguna yang sedang login tidak ditemukan.'
        );

        $status = strtolower(
            trim((string) $user->status)
        );

        $role = strtolower(
            trim((string) $user->role)
        );

        abort_unless(
            $status === 'aktif',
            403,
            'Akun tidak ditemukan atau sudah dinonaktifkan.'
        );

        abort_unless(
            $role === 'auditor',
            403,
            'Halaman ini hanya dapat diakses oleh Auditor.'
        );

        return (int) $user->id;
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY PERIODE SESUAI TIM AMI
    |--------------------------------------------------------------------------
    |
    | Auditor hanya dapat mengakses periode AMI ketika ID pengguna
    | tercatat pada tabel tim_ami untuk periode tersebut.
    |
    */

    private function periodeAuditorQuery(
        int $auditorId
    ): Builder {
        return PeriodeAmi::query()
            ->whereHas(
                'tim',
                function ($query) use ($auditorId) {
                    $query->where(
                        'id_user',
                        $auditorId
                    );
                }
            );
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY KESIMPULAN SESUAI TIM AMI
    |--------------------------------------------------------------------------
    */

    private function kesimpulanAuditorQuery(
        int $auditorId
    ): Builder {
        return KesimpulanAudit::query()
            ->whereHas(
                'periodeAmi.tim',
                function ($query) use ($auditorId) {
                    $query->where(
                        'id_user',
                        $auditorId
                    );
                }
            );
    }

    /*
    |--------------------------------------------------------------------------
    | CARI PERIODE SESUAI PENUGASAN AUDITOR
    |--------------------------------------------------------------------------
    |
    | Digunakan kembali pada store dan update agar id_periode_ami
    | tidak hanya diperiksa dengan exists, tetapi juga harus termasuk
    | dalam penugasan Auditor pada tabel tim_ami.
    |
    */

    private function findPeriodeAuditorOrFail(
        int $periodeId,
        int $auditorId
    ): PeriodeAmi {
        return $this
            ->periodeAuditorQuery($auditorId)
            ->findOrFail($periodeId);
    }

    /*
    |--------------------------------------------------------------------------
    | CARI KESIMPULAN SESUAI PENUGASAN AUDITOR
    |--------------------------------------------------------------------------
    */

    private function findKesimpulanAuditorOrFail(
        int $kesimpulanId,
        int $auditorId
    ): KesimpulanAudit {
        return $this
            ->kesimpulanAuditorQuery($auditorId)
            ->with([
                'periodeAmi',
                'user',
            ])
            ->findOrFail($kesimpulanId);
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    |
    | Hanya menampilkan kesimpulan dari periode yang ditugaskan
    | kepada Auditor yang sedang login.
    |
    */

    public function index(): View
    {
        $auditorId = $this->currentUserId();

        $data = $this
            ->kesimpulanAuditorQuery($auditorId)
            ->with([
                'periodeAmi',
                'user',
            ])
            ->orderByDesc('id')
            ->get();

        return view(
            'auditor.kesimpulan.index',
            compact('data')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    |
    | Daftar periode hanya berisi periode berjalan yang menjadi
    | penugasan Auditor pada tabel tim_ami.
    |
    */

    public function create(
        Request $request
    ): View {
        $auditorId = $this->currentUserId();

        $periodeAmi = $this
            ->periodeAuditorQuery($auditorId)
            ->select([
                'id',
                'tahun',
                'status',
            ])
            ->whereRaw(
                'LOWER(TRIM(status)) = ?',
                ['berjalan']
            )
            ->orderByDesc('tahun')
            ->orderByDesc('id')
            ->get();

        $periodeTerpilih = $request->query(
            'id_periode_ami'
        );

        /*
        |--------------------------------------------------------------------------
        | CEK PERIODE DARI QUERY STRING
        |--------------------------------------------------------------------------
        |
        | Jika id_periode_ami dikirim melalui URL, pastikan periode tersebut
        | benar-benar termasuk periode penugasan Auditor dan masih berjalan.
        |
        */

        if ($periodeTerpilih) {
            $periodeValid = $periodeAmi->contains(
                'id',
                (int) $periodeTerpilih
            );

            if (!$periodeValid) {
                $periodeTerpilih = null;
            }
        }

        return view(
            'auditor.kesimpulan.create',
            compact(
                'periodeAmi',
                'periodeTerpilih'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    |
    | id_periode_ami diperiksa dua kali:
    |
    | 1. Harus ada pada tabel periode_ami.
    | 2. Harus termasuk penugasan Auditor pada tabel tim_ami.
    |
    */

    public function store(
        Request $request
    ): RedirectResponse {
        $auditorId = $this->currentUserId();

        $validated = $request->validate(
            [
                'id_periode_ami' => [
                    'required',
                    'integer',
                    'exists:periode_ami,id',
                ],

                'kesimpulan' => [
                    'required',
                    'string',
                    'max:10000',
                ],
            ],
            [
                'id_periode_ami.required' =>
                    'Periode AMI wajib dipilih.',

                'id_periode_ami.integer' =>
                    'Periode AMI yang dipilih tidak valid.',

                'id_periode_ami.exists' =>
                    'Periode AMI yang dipilih tidak ditemukan.',

                'kesimpulan.required' =>
                    'Kesimpulan audit wajib diisi.',

                'kesimpulan.string' =>
                    'Kesimpulan audit harus berupa teks.',

                'kesimpulan.max' =>
                    'Kesimpulan audit maksimal 10.000 karakter.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | VALIDASI ULANG BERDASARKAN TIM AMI
        |--------------------------------------------------------------------------
        */

        $periode = $this->findPeriodeAuditorOrFail(
            (int) $validated['id_periode_ami'],
            $auditorId
        );

        $this->abortIfPeriodeClosed($periode);

        /*
        |--------------------------------------------------------------------------
        | PERIODE HARUS BERSTATUS BERJALAN
        |--------------------------------------------------------------------------
        */

        $statusPeriode = strtolower(
            trim((string) $periode->status)
        );

        if ($statusPeriode !== 'berjalan') {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Kesimpulan hanya dapat dibuat pada periode AMI yang sedang berjalan.'
                );
        }

        DB::transaction(
            function () use (
                $validated,
                $periode,
                $auditorId
            ) {
                KesimpulanAudit::create([
                    'id_periode_ami' =>
                        $periode->id,

                    'kesimpulan' =>
                        trim(
                            $validated['kesimpulan']
                        ),

                    'id_user' =>
                        $auditorId,
                ]);
            }
        );

        return redirect()
            ->route('auditor.temuan.index')
            ->with(
                'success',
                'Kesimpulan audit berhasil disimpan.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    |
    | ID kesimpulan pada URL harus berasal dari periode penugasan Auditor.
    |
    */

    public function show(
        int $id
    ): View {
        $auditorId = $this->currentUserId();

        $kesimpulan = $this
            ->findKesimpulanAuditorOrFail(
                $id,
                $auditorId
            );

        return view(
            'auditor.kesimpulan.show',
            compact('kesimpulan')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    |
    | Kesimpulan dan daftar periode sama-sama difilter berdasarkan tim_ami.
    |
    */

    public function edit(
        int $id
    ): View {
        $auditorId = $this->currentUserId();

        $kesimpulan = $this
            ->findKesimpulanAuditorOrFail(
                $id,
                $auditorId
            );

        $this->abortIfPeriodeClosed(
            $kesimpulan->periodeAmi
        );

        $periodeAmi = $this
            ->periodeAuditorQuery($auditorId)
            ->select([
                'id',
                'tahun',
                'status',
            ])
            ->orderByDesc('tahun')
            ->orderByDesc('id')
            ->get();

        return view(
            'auditor.kesimpulan.edit',
            compact(
                'kesimpulan',
                'periodeAmi'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    |
    | Kesimpulan lama dan periode tujuan harus sama-sama berada dalam
    | penugasan Auditor yang sedang login.
    |
    */

    public function update(
        Request $request,
        int $id
    ): RedirectResponse {
        $auditorId = $this->currentUserId();

        /*
        |--------------------------------------------------------------------------
        | CEK KESIMPULAN YANG AKAN DIUBAH
        |--------------------------------------------------------------------------
        */

        $kesimpulan = $this
            ->findKesimpulanAuditorOrFail(
                $id,
                $auditorId
            );

        $this->abortIfPeriodeClosed(
            $kesimpulan->periodeAmi
        );

        $validated = $request->validate(
            [
                'id_periode_ami' => [
                    'required',
                    'integer',
                    'exists:periode_ami,id',
                ],

                'kesimpulan' => [
                    'required',
                    'string',
                    'max:10000',
                ],
            ],
            [
                'id_periode_ami.required' =>
                    'Periode AMI wajib dipilih.',

                'id_periode_ami.integer' =>
                    'Periode AMI yang dipilih tidak valid.',

                'id_periode_ami.exists' =>
                    'Periode AMI yang dipilih tidak ditemukan.',

                'kesimpulan.required' =>
                    'Kesimpulan audit wajib diisi.',

                'kesimpulan.string' =>
                    'Kesimpulan audit harus berupa teks.',

                'kesimpulan.max' =>
                    'Kesimpulan audit maksimal 10.000 karakter.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | CEK PERIODE TUJUAN BERDASARKAN TIM AMI
        |--------------------------------------------------------------------------
        */

        $periode = $this->findPeriodeAuditorOrFail(
            (int) $validated['id_periode_ami'],
            $auditorId
        );

        $this->abortIfPeriodeClosed($periode);

        DB::transaction(
            function () use (
                $kesimpulan,
                $validated,
                $periode,
                $auditorId
            ) {
                $kesimpulan->update([
                    'id_periode_ami' =>
                        $periode->id,

                    'kesimpulan' =>
                        trim(
                            $validated['kesimpulan']
                        ),

                    'id_user' =>
                        $auditorId,
                ]);
            }
        );

        return redirect()
            ->route('auditor.temuan.index')
            ->with(
                'success',
                'Kesimpulan audit berhasil diperbarui.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    |
    | Auditor hanya dapat menghapus kesimpulan dari periode penugasannya.
    |
    */

    public function destroy(
        int $id
    ): RedirectResponse {
        $auditorId = $this->currentUserId();

        $kesimpulan = $this
            ->findKesimpulanAuditorOrFail(
                $id,
                $auditorId
            );

        $this->abortIfPeriodeClosed(
            $kesimpulan->periodeAmi
        );

        DB::transaction(
            function () use ($kesimpulan) {
                $kesimpulan->delete();
            }
        );

        return redirect()
            ->route('auditor.temuan.index')
            ->with(
                'success',
                'Kesimpulan audit berhasil dihapus.'
            );
    }
}