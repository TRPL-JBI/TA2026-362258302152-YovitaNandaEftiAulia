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

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index($periode)
    {
        $periodeAmi = PeriodeAmi::findOrFail($periode);

        $data = TimAmi::with('user')
            ->where('id_periode_ami', $periodeAmi->id)
            ->orderByRaw("
                CASE
                    WHEN LOWER(TRIM(role)) = 'ketua auditor' THEN 1
                    WHEN LOWER(TRIM(role)) = 'auditor' THEN 2
                    WHEN LOWER(TRIM(role)) = 'auditee' THEN 3
                    ELSE 4
                END
            ")
            ->orderBy('id')
            ->get();

        $jumlahKetuaAuditor = $this->jumlahRoleDalamPeriode(
            $periodeAmi->id,
            'ketua auditor'
        );

        $jumlahAuditor = $this->jumlahRoleDalamPeriode(
            $periodeAmi->id,
            'auditor'
        );

        $jumlahAuditee = $this->jumlahRoleDalamPeriode(
            $periodeAmi->id,
            'auditee'
        );

        $komposisiLengkap =
            $jumlahKetuaAuditor > 0
            && $jumlahAuditee > 0;

        return view(
            'tim_ami.index',
            compact(
                'periodeAmi',
                'data',
                'jumlahKetuaAuditor',
                'jumlahAuditor',
                'jumlahAuditee',
                'komposisiLengkap'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create($periode)
    {
        $periodeAmi = PeriodeAmi::findOrFail($periode);

        $this->abortIfPeriodeClosed($periodeAmi);

        /*
        |--------------------------------------------------------------------------
        | ID USER YANG SUDAH MASUK TIM
        |--------------------------------------------------------------------------
        */

        $idUserSudahDipakai = TimAmi::query()
            ->where('id_periode_ami', $periodeAmi->id)
            ->pluck('id_user')
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | NAMA USER YANG SUDAH MASUK TIM
        |--------------------------------------------------------------------------
        |
        | Ini diperlukan karena pada database bisa saja terdapat
        | dua ID user berbeda tetapi mempunyai nama yang sama.
        |
        */

        $namaSudahDipakai = TimAmi::query()
            ->join(
                'users',
                'users.id',
                '=',
                'tim_ami.id_user'
            )
            ->where(
                'tim_ami.id_periode_ami',
                $periodeAmi->id
            )
            ->selectRaw(
                'LOWER(TRIM(users.nama)) AS nama_normal'
            )
            ->pluck('nama_normal')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | USER YANG BOLEH DIPILIH
        |--------------------------------------------------------------------------
        |
        | Syarat:
        | - aktif
        | - ID belum digunakan
        | - nama belum digunakan
        |
        */

        $users = User::query()
            ->whereRaw(
                'LOWER(TRIM(status)) = ?',
                ['aktif']
            )
            ->when(
                !empty($idUserSudahDipakai),
                function ($query) use ($idUserSudahDipakai) {
                    $query->whereNotIn(
                        'id',
                        $idUserSudahDipakai
                    );
                }
            )
            ->when(
                !empty($namaSudahDipakai),
                function ($query) use ($namaSudahDipakai) {
                    $placeholders = implode(
                        ',',
                        array_fill(
                            0,
                            count($namaSudahDipakai),
                            '?'
                        )
                    );

                    $query->whereRaw(
                        "LOWER(TRIM(nama)) NOT IN ($placeholders)",
                        $namaSudahDipakai
                    );
                }
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

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        $periode
    ) {
        $periodeAmi = PeriodeAmi::findOrFail($periode);

        $this->abortIfPeriodeClosed($periodeAmi);

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
                        function ($query) use ($periodeAmi) {
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

                'id_user.exists' =>
                    'Pengguna tidak ditemukan atau sudah tidak aktif.',

                'id_user.unique' =>
                    'Pengguna tersebut sudah berada di Tim AMI.',

                'role.required' =>
                    'Role wajib dipilih.',

                'role.in' =>
                    'Role hanya boleh Ketua Auditor, Auditor, atau Auditee.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | AMBIL USER YANG DIPILIH
        |--------------------------------------------------------------------------
        */

        $userDipilih = User::findOrFail(
            $validated['id_user']
        );

        $namaDipilih = strtolower(
            trim(
                (string) $userDipilih->nama
            )
        );

        /*
        |--------------------------------------------------------------------------
        | CEK NAMA YANG SAMA
        |--------------------------------------------------------------------------
        |
        | Walaupun ID user berbeda, nama yang sama
        | tidak boleh masuk dua kali dalam periode yang sama.
        |
        */

        $namaSudahAda = TimAmi::query()
            ->join(
                'users',
                'users.id',
                '=',
                'tim_ami.id_user'
            )
            ->where(
                'tim_ami.id_periode_ami',
                $periodeAmi->id
            )
            ->whereRaw(
                'LOWER(TRIM(users.nama)) = ?',
                [$namaDipilih]
            )
            ->exists();

        abort_if(
            $namaSudahAda,
            422,
            'Nama pengguna tersebut sudah terdaftar dalam Tim AMI pada periode ini.'
        );

        TimAmi::create([
            'id_periode_ami' =>
                $periodeAmi->id,

            'id_user' =>
                $userDipilih->id,

            'role' =>
                strtolower(
                    trim(
                        $validated['role']
                    )
                ),
        ]);

        return redirect()
            ->route(
                'tim-ami.index',
                $periodeAmi->id
            )
            ->with(
                'success',
                'Anggota Tim AMI berhasil ditambahkan.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
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

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $tim = TimAmi::with('user')
            ->findOrFail($id);

        $periodeAmi = PeriodeAmi::findOrFail(
            $tim->id_periode_ami
        );

        $this->abortIfPeriodeClosed($periodeAmi);

        /*
        |--------------------------------------------------------------------------
        | ANGGOTA LAIN DALAM PERIODE
        |--------------------------------------------------------------------------
        */

        $anggotaLain = TimAmi::query()
            ->where(
                'id_periode_ami',
                $periodeAmi->id
            )
            ->where(
                'id',
                '!=',
                $tim->id
            );

        $idUserSudahDipakai = (clone $anggotaLain)
            ->pluck('id_user')
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | NAMA ANGGOTA LAIN
        |--------------------------------------------------------------------------
        */

        $namaSudahDipakai = TimAmi::query()
            ->join(
                'users',
                'users.id',
                '=',
                'tim_ami.id_user'
            )
            ->where(
                'tim_ami.id_periode_ami',
                $periodeAmi->id
            )
            ->where(
                'tim_ami.id',
                '!=',
                $tim->id
            )
            ->selectRaw(
                'LOWER(TRIM(users.nama)) AS nama_normal'
            )
            ->pluck('nama_normal')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | PILIHAN USER EDIT
        |--------------------------------------------------------------------------
        |
        | User saat ini tetap boleh muncul.
        |
        */

        $users = User::query()
            ->whereRaw(
                'LOWER(TRIM(status)) = ?',
                ['aktif']
            )
            ->where(
                function ($query) use (
                    $tim,
                    $idUserSudahDipakai,
                    $namaSudahDipakai
                ) {
                    /*
                    |--------------------------------------------------------------------------
                    | USER SAAT INI
                    |--------------------------------------------------------------------------
                    */

                    $query->where(
                        'id',
                        $tim->id_user
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | ATAU USER BARU YANG BELUM DIPAKAI
                    |--------------------------------------------------------------------------
                    */

                    $query->orWhere(
                        function ($subQuery) use (
                            $idUserSudahDipakai,
                            $namaSudahDipakai
                        ) {
                            if (!empty($idUserSudahDipakai)) {
                                $subQuery->whereNotIn(
                                    'id',
                                    $idUserSudahDipakai
                                );
                            }

                            if (!empty($namaSudahDipakai)) {
                                $placeholders = implode(
                                    ',',
                                    array_fill(
                                        0,
                                        count($namaSudahDipakai),
                                        '?'
                                    )
                                );

                                $subQuery->whereRaw(
                                    "LOWER(TRIM(nama)) NOT IN ($placeholders)",
                                    $namaSudahDipakai
                                );
                            }
                        }
                    );
                }
            )
            ->orderBy('nama')
            ->get();

        return view(
            'tim_ami.edit',
            compact(
                'tim',
                'periodeAmi',
                'users'
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
    ) {
        $tim = TimAmi::findOrFail($id);

        $periodeAmi = PeriodeAmi::findOrFail(
            $tim->id_periode_ami
        );

        $this->abortIfPeriodeClosed($periodeAmi);

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
                            function ($query) use ($periodeAmi) {
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
            ]
        );

        $userDipilih = User::findOrFail(
            $validated['id_user']
        );

        $namaDipilih = strtolower(
            trim(
                (string) $userDipilih->nama
            )
        );

        /*
        |--------------------------------------------------------------------------
        | CEK DUPLIKAT NAMA
        |--------------------------------------------------------------------------
        */

        $namaSudahAda = TimAmi::query()
            ->join(
                'users',
                'users.id',
                '=',
                'tim_ami.id_user'
            )
            ->where(
                'tim_ami.id_periode_ami',
                $periodeAmi->id
            )
            ->where(
                'tim_ami.id',
                '!=',
                $tim->id
            )
            ->whereRaw(
                'LOWER(TRIM(users.nama)) = ?',
                [$namaDipilih]
            )
            ->exists();

        abort_if(
            $namaSudahAda,
            422,
            'Nama pengguna tersebut sudah terdaftar dalam Tim AMI pada periode ini.'
        );

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

        /*
        |--------------------------------------------------------------------------
        | KETUA AUDITOR TERAKHIR
        |--------------------------------------------------------------------------
        */

        if (
            $roleLama === 'ketua auditor'
            && $roleBaru !== 'ketua auditor'
        ) {
            abort_if(
                $this->jumlahRoleDalamPeriode(
                    $periodeAmi->id,
                    'ketua auditor'
                ) <= 1,
                422,
                'Periode AMI harus memiliki minimal satu Ketua Auditor.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | AUDITEE TERAKHIR
        |--------------------------------------------------------------------------
        */

        if (
            $roleLama === 'auditee'
            && $roleBaru !== 'auditee'
        ) {
            abort_if(
                $this->jumlahRoleDalamPeriode(
                    $periodeAmi->id,
                    'auditee'
                ) <= 1,
                422,
                'Periode AMI harus memiliki minimal satu Auditee.'
            );
        }

        $tim->update([
            'id_user' =>
                $userDipilih->id,

            'role' =>
                $roleBaru,
        ]);

        return redirect()
            ->route(
                'tim-ami.index',
                $periodeAmi->id
            )
            ->with(
                'success',
                'Anggota Tim AMI berhasil diperbarui.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $tim = TimAmi::findOrFail($id);

        $periodeAmi = PeriodeAmi::findOrFail(
            $tim->id_periode_ami
        );

        $this->abortIfPeriodeClosed($periodeAmi);

        $role = strtolower(
            trim(
                (string) $tim->role
            )
        );

        if ($role === 'ketua auditor') {
            abort_if(
                $this->jumlahRoleDalamPeriode(
                    $periodeAmi->id,
                    'ketua auditor'
                ) <= 1,
                422,
                'Ketua Auditor terakhir tidak dapat dihapus.'
            );
        }

        if ($role === 'auditee') {
            abort_if(
                $this->jumlahRoleDalamPeriode(
                    $periodeAmi->id,
                    'auditee'
                ) <= 1,
                422,
                'Auditee terakhir tidak dapat dihapus.'
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
                'Anggota Tim AMI berhasil dihapus.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | HITUNG ROLE
    |--------------------------------------------------------------------------
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