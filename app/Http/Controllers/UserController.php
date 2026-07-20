<?php

namespace App\Http\Controllers;

use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ATURAN PASSWORD
    |--------------------------------------------------------------------------
    |
    | Pada aplikasi normal:
    | - minimal 8 karakter;
    | - huruf besar dan kecil;
    | - angka;
    | - simbol;
    | - diperiksa terhadap database password bocor.
    |
    | Pada environment testing, pemeriksaan password bocor dilewati karena
    | membutuhkan layanan internet eksternal. Aturan lainnya tetap diuji.
    |
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

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $data = User::with('unit')
            ->orderBy('nama')
            ->get();

        return view(
            'users.index',
            compact('data')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $unit = UnitKerja::orderBy('nama')->get();

        return view(
            'users.create',
            compact('unit')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
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

                'id_unit_kerja' => [
                    'required',
                    'integer',
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
                    'Password ini pernah ditemukan dalam kebocoran data. Gunakan password lain.',

                'role.required' =>
                    'Role pengguna wajib dipilih.',

                'role.in' =>
                    'Role pengguna tidak valid.',

                'id_unit_kerja.required' =>
                    'Unit kerja wajib dipilih.',

                'id_unit_kerja.integer' =>
                    'Unit kerja tidak valid.',

                'id_unit_kerja.exists' =>
                    'Unit kerja tidak ditemukan.',

                'status.required' =>
                    'Status pengguna wajib dipilih.',

                'status.in' =>
                    'Status pengguna tidak valid.',
            ]
        );

        User::create([
            'nama' => trim($validated['nama']),

            'email' => strtolower(
                trim($validated['email'])
            ),

            'password' => Hash::make(
                $validated['password']
            ),

            'role' => $validated['role'],

            'id_unit_kerja' =>
                $validated['id_unit_kerja'],

            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('user.index')
            ->with(
                'success',
                'User berhasil ditambahkan.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $data = User::with('unit')
            ->findOrFail($id);

        return view(
            'users.show',
            compact('data')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $data = User::findOrFail($id);

        $unit = UnitKerja::orderBy('nama')->get();

        return view(
            'users.edit',
            compact(
                'data',
                'unit'
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

                'id_unit_kerja' => [
                    'required',
                    'integer',
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
                    'Password ini pernah ditemukan dalam kebocoran data. Gunakan password lain.',

                'role.required' =>
                    'Role pengguna wajib dipilih.',

                'role.in' =>
                    'Role pengguna tidak valid.',

                'id_unit_kerja.required' =>
                    'Unit kerja wajib dipilih.',

                'id_unit_kerja.integer' =>
                    'Unit kerja tidak valid.',

                'id_unit_kerja.exists' =>
                    'Unit kerja tidak ditemukan.',

                'status.required' =>
                    'Status pengguna wajib dipilih.',

                'status.in' =>
                    'Status pengguna tidak valid.',
            ]
        );

        $updateData = [
            'nama' => trim($validated['nama']),

            'email' => strtolower(
                trim($validated['email'])
            ),

            'role' => $validated['role'],

            'id_unit_kerja' =>
                $validated['id_unit_kerja'],

            'status' => $validated['status'],
        ];

        /*
        |--------------------------------------------------------------------------
        | PASSWORD HANYA DIUBAH JIKA DIISI
        |--------------------------------------------------------------------------
        */

        if (
            isset($validated['password'])
            && trim((string) $validated['password']) !== ''
        ) {
            $updateData['password'] = Hash::make(
                $validated['password']
            );
        }

        $data->update($updateData);

        return redirect()
            ->route('user.index')
            ->with(
                'success',
                'User berhasil diperbarui.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | AMBIL USER YANG SEDANG LOGIN
        |--------------------------------------------------------------------------
        |
        | Session user dapat berupa object atau array, sehingga keduanya
        | ditangani agar aman.
        |
        */

        $loginUser = session('user');

        $loginUserId = null;

        if (is_object($loginUser)) {
            $loginUserId = $loginUser->id ?? null;
        }

        if (is_array($loginUser)) {
            $loginUserId = $loginUser['id'] ?? null;
        }

        /*
        |--------------------------------------------------------------------------
        | CEGAH USER MENGHAPUS AKUN SENDIRI
        |--------------------------------------------------------------------------
        */

        if (
            $loginUserId !== null
            && (int) $loginUserId === (int) $user->id
        ) {
            return redirect()
                ->route('user.index')
                ->with(
                    'error',
                    'Akun yang sedang digunakan tidak dapat dihapus.'
                );
        }

        $user->delete();

        return redirect()
            ->route('user.index')
            ->with(
                'success',
                'User berhasil dihapus.'
            );
    }
}