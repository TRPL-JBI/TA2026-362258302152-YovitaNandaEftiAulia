<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HALAMAN LOGIN
    |--------------------------------------------------------------------------
    |
    | Method index dipertahankan karena route login saat ini memanggil index.
    |
    */

    public function index(): View
    {
        return view('auth.login');
    }

    /*
    |--------------------------------------------------------------------------
    | ALIAS HALAMAN LOGIN
    |--------------------------------------------------------------------------
    |
    | Method ini tetap disediakan agar route yang memakai showLogin
    | juga tetap dapat digunakan.
    |
    */

    public function showLogin(): View
    {
        return view('auth.login');
    }

    /*
    |--------------------------------------------------------------------------
    | PROSES LOGIN
    |--------------------------------------------------------------------------
    */

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'username' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'password' => [
                    'required',
                    'string',
                ],
            ],
            [
                'username.required' =>
                    'Username wajib diisi.',

                'password.required' =>
                    'Password wajib diisi.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | CARI PENGGUNA
        |--------------------------------------------------------------------------
        |
        | Form memakai nama input username, tetapi database memakai kolom nama.
        | Pengguna juga dapat login menggunakan email.
        |
        */

        $username = strtolower(
            trim($validated['username'])
        );

        $user = User::query()
            ->whereRaw(
                'LOWER(TRIM(nama)) = ?',
                [$username]
            )
            ->orWhereRaw(
                'LOWER(TRIM(email)) = ?',
                [$username]
            )
            ->first();

        /*
        |--------------------------------------------------------------------------
        | PERIKSA PASSWORD
        |--------------------------------------------------------------------------
        */

        if (
            !$user
            || !Hash::check(
                $validated['password'],
                $user->password
            )
        ) {
            return back()
                ->withInput(
                    $request->only('username')
                )
                ->withErrors([
                    'username' =>
                        'Username atau password salah.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | NORMALISASI STATUS DAN ROLE
        |--------------------------------------------------------------------------
        */

        $status = strtolower(
            trim((string) $user->status)
        );

        $role = strtolower(
            trim((string) $user->role)
        );

        /*
        |--------------------------------------------------------------------------
        | PERIKSA STATUS AKUN
        |--------------------------------------------------------------------------
        */

        if ($status !== 'aktif') {
            return back()
                ->withInput(
                    $request->only('username')
                )
                ->withErrors([
                    'username' =>
                        'Akun Anda sedang tidak aktif.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | PERIKSA ROLE
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $role,
                [
                    'admin',
                    'auditor',
                    'auditee',
                ],
                true
            )
        ) {
            return back()
                ->withInput(
                    $request->only('username')
                )
                ->withErrors([
                    'username' =>
                        'Role pengguna tidak valid.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | HAPUS SESSION LAMA
        |--------------------------------------------------------------------------
        */

        $request->session()->forget([
            'user',
            'role',
            'nama',
            'username',
            'email',
            'id_unit_kerja',
            'user_id',
        ]);

        /*
        |--------------------------------------------------------------------------
        | SIMPAN SESSION BARU
        |--------------------------------------------------------------------------
        |
        | Sesuai revisi dosen, session hanya menyimpan user_id.
        |
        */

        $request->session()->regenerate();

        $request->session()->put(
            'user_id',
            $user->id
        );

        /*
        |--------------------------------------------------------------------------
        | REDIRECT BERDASARKAN ROLE
        |--------------------------------------------------------------------------
        */

        return match ($role) {
            'admin' => redirect()
                ->route('dashboard.admin'),

            'auditor' => redirect()
                ->route('dashboard.auditor'),

            'auditee' => redirect()
                ->route('dashboard.auditee'),

            default => redirect()
                ->route('login')
                ->withErrors([
                    'username' =>
                        'Role pengguna tidak dikenali.',
                ]),
        };
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(
        Request $request
    ): RedirectResponse {
        $request->session()->flush();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Anda berhasil logout.'
            );
    }
}