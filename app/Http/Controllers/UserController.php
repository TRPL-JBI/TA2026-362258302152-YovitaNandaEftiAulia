<?php

namespace App\Http\Controllers;

use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Aturan password pengguna.
     */
    private function passwordRule(): Password
    {
        $rule = Password::min(8)
            ->mixedCase()
            ->letters()
            ->numbers()
            ->symbols();

        if (!app()->environment('testing')) {
            $rule->uncompromised();
        }

        return $rule;
    }

    /**
     * Menampilkan daftar pengguna.
     */
    public function index()
    {
        $data = User::query()
            ->with([
                'unit',
                'unitKerjaKepala',
            ])
            ->orderBy('nama')
            ->get();

        return view('users.index', compact('data'));
    }

    /**
     * Menampilkan form tambah pengguna.
     */
    public function create()
    {
        $unit = UnitKerja::query()
            ->with('kepalaUnit')
            ->orderBy('nama')
            ->get();

        return view('users.create', compact('unit'));
    }

    /**
     * Menyimpan pengguna baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'nama' => [
                    'required',
                    'string',
                    'max:100',
                ],

                'email' => [
                    'required',
                    'email',
                    'max:150',
                    'unique:users,email',
                ],

                'password' => [
                    'required',
                    'string',
                    'confirmed',
                    $this->passwordRule(),
                ],

                'role' => [
                    'required',
                    'in:admin,auditee,auditor',
                ],

                'unit_kerja_ids' => [
                    'nullable',
                    'required_if:role,auditee',
                    'array',
                    'min:1',
                ],

                'unit_kerja_ids.*' => [
                    'integer',
                    'distinct',
                    'exists:unit_kerja,id',
                ],

                'status' => [
                    'required',
                    'in:aktif,nonaktif',
                ],
            ],
            [
                'nama.required' =>
                    'Nama pengguna wajib diisi.',

                'nama.string' =>
                    'Nama pengguna harus berupa teks.',

                'nama.max' =>
                    'Nama pengguna maksimal 100 karakter.',

                'email.required' =>
                    'Email wajib diisi.',

                'email.email' =>
                    'Format email tidak valid.',

                'email.max' =>
                    'Email maksimal 150 karakter.',

                'email.unique' =>
                    'Email sudah digunakan.',

                'password.required' =>
                    'Password wajib diisi.',

                'password.confirmed' =>
                    'Konfirmasi password tidak sama.',

                'password.min' =>
                    'Password minimal 8 karakter.',

                'password.mixed' =>
                    'Password harus memiliki huruf besar dan huruf kecil.',

                'password.letters' =>
                    'Password harus mengandung huruf.',

                'password.numbers' =>
                    'Password harus mengandung angka.',

                'password.symbols' =>
                    'Password harus mengandung simbol.',

                'password.uncompromised' =>
                    'Password pernah ditemukan dalam kebocoran data. Gunakan password lain.',

                'role.required' =>
                    'Role pengguna wajib dipilih.',

                'role.in' =>
                    'Role pengguna tidak valid.',

                'unit_kerja_ids.required_if' =>
                    'Pilih minimal satu Unit Kerja untuk user Auditee atau Kepala Unit.',

                'unit_kerja_ids.array' =>
                    'Pilihan Unit Kerja tidak valid.',

                'unit_kerja_ids.min' =>
                    'Pilih minimal satu Unit Kerja.',

                'unit_kerja_ids.*.integer' =>
                    'Pilihan Unit Kerja tidak valid.',

                'unit_kerja_ids.*.distinct' =>
                    'Unit Kerja tidak boleh dipilih lebih dari satu kali.',

                'unit_kerja_ids.*.exists' =>
                    'Salah satu Unit Kerja tidak ditemukan.',

                'status.required' =>
                    'Status pengguna wajib dipilih.',

                'status.in' =>
                    'Status pengguna tidak valid.',
            ]
        );

        DB::transaction(function () use ($validated) {
            $unitKerjaIds = $validated['role'] === 'auditee'
                ? array_values(
                    array_unique($validated['unit_kerja_ids'] ?? [])
                )
                : [];

            $user = User::create([
                'nama' => trim($validated['nama']),

                'email' => strtolower(
                    trim($validated['email'])
                ),

                'password' => Hash::make(
                    $validated['password']
                ),

                'role' => $validated['role'],

                /*
                 * Kolom lama dipertahankan sementara untuk kompatibilitas.
                 * Hubungan utama tetap unit_kerja.id_user.
                 */
                'id_unit_kerja' => $unitKerjaIds[0] ?? null,

                'status' => $validated['status'],
            ]);

            if ($validated['role'] === 'auditee') {
                UnitKerja::query()
                    ->whereIn('id', $unitKerjaIds)
                    ->update([
                        'id_user' => $user->id,
                    ]);
            }
        });

        return redirect()
            ->route('user.index')
            ->with(
                'success',
                'User berhasil ditambahkan.'
            );
    }

    /**
     * Menampilkan detail pengguna.
     */
    public function show($id)
    {
        $data = User::query()
            ->with([
                'unit',
                'unitKerjaKepala',
            ])
            ->findOrFail($id);

        return view('users.show', compact('data'));
    }

    /**
     * Menampilkan form edit pengguna.
     */
    public function edit($id)
    {
        $data = User::query()
            ->with('unitKerjaKepala')
            ->findOrFail($id);

        $unit = UnitKerja::query()
            ->with('kepalaUnit')
            ->orderBy('nama')
            ->get();

        $unitKerjaTerpilih = $data->unitKerjaKepala
            ->pluck('id')
            ->map(fn ($idUnit) => (int) $idUnit)
            ->all();

        return view(
            'users.edit',
            compact(
                'data',
                'unit',
                'unitKerjaTerpilih'
            )
        );
    }

    /**
     * Memperbarui pengguna.
     */
    public function update(
        Request $request,
        $id
    ) {
        $data = User::findOrFail($id);

        $validated = $request->validate(
            [
                'nama' => [
                    'required',
                    'string',
                    'max:100',
                ],

                'email' => [
                    'required',
                    'email',
                    'max:150',
                    'unique:users,email,' . $data->id,
                ],

                'password' => [
                    'nullable',
                    'string',
                    'confirmed',
                    $this->passwordRule(),
                ],

                'role' => [
                    'required',
                    'in:admin,auditee,auditor',
                ],

                'unit_kerja_ids' => [
                    'nullable',
                    'required_if:role,auditee',
                    'array',
                    'min:1',
                ],

                'unit_kerja_ids.*' => [
                    'integer',
                    'distinct',
                    'exists:unit_kerja,id',
                ],

                'status' => [
                    'required',
                    'in:aktif,nonaktif',
                ],
            ],
            [
                'nama.required' =>
                    'Nama pengguna wajib diisi.',

                'nama.string' =>
                    'Nama pengguna harus berupa teks.',

                'nama.max' =>
                    'Nama pengguna maksimal 100 karakter.',

                'email.required' =>
                    'Email wajib diisi.',

                'email.email' =>
                    'Format email tidak valid.',

                'email.max' =>
                    'Email maksimal 150 karakter.',

                'email.unique' =>
                    'Email sudah digunakan.',

                'password.confirmed' =>
                    'Konfirmasi password tidak sama.',

                'password.min' =>
                    'Password minimal 8 karakter.',

                'password.mixed' =>
                    'Password harus memiliki huruf besar dan huruf kecil.',

                'password.letters' =>
                    'Password harus mengandung huruf.',

                'password.numbers' =>
                    'Password harus mengandung angka.',

                'password.symbols' =>
                    'Password harus mengandung simbol.',

                'password.uncompromised' =>
                    'Password pernah ditemukan dalam kebocoran data. Gunakan password lain.',

                'role.required' =>
                    'Role pengguna wajib dipilih.',

                'role.in' =>
                    'Role pengguna tidak valid.',

                'unit_kerja_ids.required_if' =>
                    'Pilih minimal satu Unit Kerja untuk user Auditee atau Kepala Unit.',

                'unit_kerja_ids.array' =>
                    'Pilihan Unit Kerja tidak valid.',

                'unit_kerja_ids.min' =>
                    'Pilih minimal satu Unit Kerja.',

                'unit_kerja_ids.*.integer' =>
                    'Pilihan Unit Kerja tidak valid.',

                'unit_kerja_ids.*.distinct' =>
                    'Unit Kerja tidak boleh dipilih lebih dari satu kali.',

                'unit_kerja_ids.*.exists' =>
                    'Salah satu Unit Kerja tidak ditemukan.',

                'status.required' =>
                    'Status pengguna wajib dipilih.',

                'status.in' =>
                    'Status pengguna tidak valid.',
            ]
        );

        DB::transaction(function () use ($validated, $data) {
            $unitKerjaIds = $validated['role'] === 'auditee'
                ? array_values(
                    array_unique($validated['unit_kerja_ids'] ?? [])
                )
                : [];

            $updateData = [
                'nama' => trim($validated['nama']),

                'email' => strtolower(
                    trim($validated['email'])
                ),

                'role' => $validated['role'],

                /*
                 * Kolom lama dipertahankan sementara.
                 */
                'id_unit_kerja' => $unitKerjaIds[0] ?? null,

                'status' => $validated['status'],
            ];

            if (
                isset($validated['password']) &&
                trim((string) $validated['password']) !== ''
            ) {
                $updateData['password'] = Hash::make(
                    $validated['password']
                );
            }

            $data->update($updateData);

            /*
             * Lepaskan semua Unit Kerja yang sebelumnya
             * ditangani user ini.
             */
            UnitKerja::query()
                ->where('id_user', $data->id)
                ->update([
                    'id_user' => null,
                ]);

            /*
             * Pasangkan kembali Unit Kerja yang dicentang.
             */
            if (
                $validated['role'] === 'auditee' &&
                count($unitKerjaIds) > 0
            ) {
                UnitKerja::query()
                    ->whereIn('id', $unitKerjaIds)
                    ->update([
                        'id_user' => $data->id,
                    ]);
            }
        });

        return redirect()
            ->route('user.index')
            ->with(
                'success',
                'User berhasil diperbarui.'
            );
    }

    /**
     * Menghapus pengguna.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $loginUser = session('user');
        $loginUserId = null;

        if (is_object($loginUser)) {
            $loginUserId = $loginUser->id ?? null;
        }

        if (is_array($loginUser)) {
            $loginUserId = $loginUser['id'] ?? null;
        }

        if (
            $loginUserId !== null &&
            (int) $loginUserId === (int) $user->id
        ) {
            return redirect()
                ->route('user.index')
                ->with(
                    'error',
                    'Akun yang sedang digunakan tidak dapat dihapus.'
                );
        }

        DB::transaction(function () use ($user) {
            /*
             * Unit Kerja tidak ikut dihapus.
             * Hanya hubungan Kepala Unit yang dilepas.
             */
            UnitKerja::query()
                ->where('id_user', $user->id)
                ->update([
                    'id_user' => null,
                ]);

            $user->delete();
        });

        return redirect()
            ->route('user.index')
            ->with(
                'success',
                'User berhasil dihapus.'
            );
    }
}