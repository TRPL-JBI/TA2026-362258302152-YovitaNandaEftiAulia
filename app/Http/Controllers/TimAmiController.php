<?php

namespace App\Http\Controllers;

use App\Models\PeriodeAmi;
use App\Models\TimAmi;
use App\Models\User;
use App\Traits\ChecksPeriodeAmiStatus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TimAmiController extends Controller
{
    use ChecksPeriodeAmiStatus;

    /**
     * Menampilkan daftar Tim AMI.
     */
    public function index($periode)
    {
        $periodeAmi = PeriodeAmi::findOrFail($periode);

        $data = TimAmi::with('user')
            ->where(
                'id_periode_ami',
                $periodeAmi->id
            )
            ->orderBy('role')
            ->orderBy('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | INFORMASI KELENGKAPAN KOMPOSISI TIM
        |--------------------------------------------------------------------------
        */

        $jumlahKetuaAuditor = TimAmi::query()
            ->where(
                'id_periode_ami',
                $periodeAmi->id
            )
            ->whereRaw(
                'LOWER(TRIM(role)) = ?',
                ['ketua auditor']
            )
            ->count();

        $jumlahAuditee = TimAmi::query()
            ->where(
                'id_periode_ami',
                $periodeAmi->id
            )
            ->whereRaw(
                'LOWER(TRIM(role)) = ?',
                ['auditee']
            )
            ->count();

        $komposisiLengkap =
            $jumlahKetuaAuditor > 0
            && $jumlahAuditee > 0;

        return view(
            'tim_ami.index',
            compact(
                'periodeAmi',
                'data',
                'jumlahKetuaAuditor',
                'jumlahAuditee',
                'komposisiLengkap'
            )
        );
    }

    /**
     * Menampilkan form tambah anggota Tim AMI.
     */
    public function create($periode)
    {
        $periodeAmi = PeriodeAmi::findOrFail($periode);

        $this->abortIfPeriodeClosed(
            $periodeAmi
        );

        /*
        |--------------------------------------------------------------------------
        | HANYA MENAMPILKAN USER AKTIF
        |--------------------------------------------------------------------------
        */

        $users = User::query()
            ->whereRaw(
                'LOWER(TRIM(status)) = ?',
                ['aktif']
            )
            ->orderBy('nama')
            ->get();

        return view(
            'tim_ami.create',
            compact(
                'periodeAmi',
                'users'
            )
        );
    }

    /**
     * Menyimpan anggota Tim AMI.
     */
    public function store(
        Request $request,
        $periode
    ) {
        $periodeAmi = PeriodeAmi::findOrFail(
            $periode
        );

        $this->abortIfPeriodeClosed(
            $periodeAmi
        );

        $validated = $request->validate(
            [
                'id_user' => [
                    'required',
                    'integer',

                    Rule::exists(
                        'users',
                        'id'
                    )->where(
                        function ($query) {
                            $query->whereRaw(
                                'LOWER(TRIM(status)) = ?',
                                ['aktif']
                            );
                        }
                    ),

                    Rule::unique(
                        'tim_ami',
                        'id_user'
                    )->where(
                        function ($query) use (
                            $periodeAmi
                        ) {
                            $query->where(
                                'id_periode_ami',
                                $periodeAmi->id
                            );
                        }
                    ),
                ],

                'role' => [
                    'required',
                    'string',

                    Rule::in([
                        'ketua auditor',
                        'auditor',
                        'auditee',
                    ]),
                ],
            ],
            [
                'id_user.required' =>
                    'Pengguna wajib dipilih.',

                'id_user.integer' =>
                    'Pengguna yang dipilih tidak valid.',

                'id_user.exists' =>
                    'Pengguna tidak ditemukan atau sudah tidak aktif.',

                'id_user.unique' =>
                    'Pengguna tersebut sudah terdaftar dalam Tim AMI pada periode ini.',

                'role.required' =>
                    'Role anggota Tim AMI wajib dipilih.',

                'role.string' =>
                    'Role anggota Tim AMI tidak valid.',

                'role.in' =>
                    'Role hanya boleh Ketua Auditor, Auditor, atau Auditee.',
            ]
        );

        TimAmi::create([
            'id_periode_ami' =>
                $periodeAmi->id,

            'id_user' =>
                $validated['id_user'],

            'role' =>
                $validated['role'],
        ]);

        return redirect()
            ->route(
                'tim-ami.index',
                $periodeAmi->id
            )
            ->with(
                'success',
                'Tim AMI berhasil ditambahkan.'
            );
    }

    /**
     * Menampilkan detail anggota Tim AMI.
     */
    public function show($id)
    {
        $tim = TimAmi::with([
            'user',
            'periode',
        ])->findOrFail($id);

        return view(
            'tim_ami.show',
            compact('tim')
        );
    }

    /**
     * Menampilkan form edit anggota Tim AMI.
     */
    public function edit($id)
    {
        $tim = TimAmi::findOrFail($id);

        $periodeAmi = PeriodeAmi::findOrFail(
            $tim->id_periode_ami
        );

        $this->abortIfPeriodeClosed(
            $periodeAmi
        );

        $users = User::query()
            ->whereRaw(
                'LOWER(TRIM(status)) = ?',
                ['aktif']
            )
            ->orderBy('nama')
            ->get();

        return view(
            'tim_ami.edit',
            compact(
                'tim',
                'users'
            )
        );
    }

    /**
     * Memperbarui anggota Tim AMI.
     */
    public function update(
        Request $request,
        $id
    ) {
        $tim = TimAmi::findOrFail($id);

        $periodeAmi = PeriodeAmi::findOrFail(
            $tim->id_periode_ami
        );

        $this->abortIfPeriodeClosed(
            $periodeAmi
        );

        $validated = $request->validate(
            [
                'id_user' => [
                    'required',
                    'integer',

                    Rule::exists(
                        'users',
                        'id'
                    )->where(
                        function ($query) {
                            $query->whereRaw(
                                'LOWER(TRIM(status)) = ?',
                                ['aktif']
                            );
                        }
                    ),

                    Rule::unique(
                        'tim_ami',
                        'id_user'
                    )
                        ->where(
                            function ($query) use (
                                $periodeAmi
                            ) {
                                $query->where(
                                    'id_periode_ami',
                                    $periodeAmi->id
                                );
                            }
                        )
                        ->ignore($tim->id),
                ],

                'role' => [
                    'required',
                    'string',

                    Rule::in([
                        'ketua auditor',
                        'auditor',
                        'auditee',
                    ]),
                ],
            ],
            [
                'id_user.required' =>
                    'Pengguna wajib dipilih.',

                'id_user.integer' =>
                    'Pengguna yang dipilih tidak valid.',

                'id_user.exists' =>
                    'Pengguna tidak ditemukan atau sudah tidak aktif.',

                'id_user.unique' =>
                    'Pengguna tersebut sudah terdaftar dalam Tim AMI pada periode ini.',

                'role.required' =>
                    'Role anggota Tim AMI wajib dipilih.',

                'role.string' =>
                    'Role anggota Tim AMI tidak valid.',

                'role.in' =>
                    'Role hanya boleh Ketua Auditor, Auditor, atau Auditee.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | CEK KOMPOSISI TIM SEBELUM ROLE DIUBAH
        |--------------------------------------------------------------------------
        |
        | Ketua auditor terakhir tidak boleh diubah menjadi role lain.
        | Auditee terakhir tidak boleh diubah menjadi role lain.
        |
        */

        $roleLama = strtolower(
            trim(
                (string) $tim->role
            )
        );

        $roleBaru = strtolower(
            trim(
                (string) $validated['role']
            )
        );

        if (
            $roleLama === 'ketua auditor'
            && $roleBaru !== 'ketua auditor'
        ) {
            $jumlahKetuaAuditor =
                $this->jumlahRoleDalamPeriode(
                    $periodeAmi->id,
                    'ketua auditor'
                );

            abort_if(
                $jumlahKetuaAuditor <= 1,
                422,
                'Role tidak dapat diubah karena periode ini harus memiliki minimal satu Ketua Auditor.'
            );
        }

        if (
            $roleLama === 'auditee'
            && $roleBaru !== 'auditee'
        ) {
            $jumlahAuditee =
                $this->jumlahRoleDalamPeriode(
                    $periodeAmi->id,
                    'auditee'
                );

            abort_if(
                $jumlahAuditee <= 1,
                422,
                'Role tidak dapat diubah karena periode ini harus memiliki minimal satu Auditee.'
            );
        }

        $tim->update([
            'id_user' =>
                $validated['id_user'],

            'role' =>
                $validated['role'],
        ]);

        return redirect()
            ->route(
                'tim-ami.index',
                $periodeAmi->id
            )
            ->with(
                'success',
                'Tim AMI berhasil diperbarui.'
            );
    }

    /**
     * Menghapus anggota Tim AMI.
     */
    public function destroy($id)
    {
        $tim = TimAmi::findOrFail($id);

        $periodeAmi = PeriodeAmi::findOrFail(
            $tim->id_periode_ami
        );

        $this->abortIfPeriodeClosed(
            $periodeAmi
        );

        /*
        |--------------------------------------------------------------------------
        | CEK KOMPOSISI TIM SEBELUM DELETE
        |--------------------------------------------------------------------------
        */

        $role = strtolower(
            trim(
                (string) $tim->role
            )
        );

        if ($role === 'ketua auditor') {
            $jumlahKetuaAuditor =
                $this->jumlahRoleDalamPeriode(
                    $periodeAmi->id,
                    'ketua auditor'
                );

            abort_if(
                $jumlahKetuaAuditor <= 1,
                422,
                'Ketua Auditor tidak dapat dihapus karena periode ini harus memiliki minimal satu Ketua Auditor.'
            );
        }

        if ($role === 'auditee') {
            $jumlahAuditee =
                $this->jumlahRoleDalamPeriode(
                    $periodeAmi->id,
                    'auditee'
                );

            abort_if(
                $jumlahAuditee <= 1,
                422,
                'Auditee tidak dapat dihapus karena periode ini harus memiliki minimal satu Auditee.'
            );
        }

        $periodeId = $periodeAmi->id;

        $tim->delete();

        return redirect()
            ->route(
                'tim-ami.index',
                $periodeId
            )
            ->with(
                'success',
                'Tim AMI berhasil dihapus.'
            );
    }

    /**
     * Menghitung jumlah anggota berdasarkan role pada satu periode.
     */
    private function jumlahRoleDalamPeriode(
        int $periodeId,
        string $role
    ): int {
        return TimAmi::query()
            ->where(
                'id_periode_ami',
                $periodeId
            )
            ->whereRaw(
                'LOWER(TRIM(role)) = ?',
                [
                    strtolower(
                        trim($role)
                    ),
                ]
            )
            ->count();
    }
}