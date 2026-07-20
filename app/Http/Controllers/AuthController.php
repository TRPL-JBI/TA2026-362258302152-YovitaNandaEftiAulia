<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function showLogin()
    {
        $loginUser = session('user');

        if ($loginUser) {
            $role = is_array($loginUser)
                ? ($loginUser['role'] ?? null)
                : ($loginUser->role ?? null);

            return match ($role) {
                'admin' => redirect()->route('dashboard'),
                'auditee' => redirect()->route('dashboard.auditee'),
                'auditor' => redirect()->route('dashboard.auditor'),
                default => view('auth.login'),
            };
        }

        return view('auth.login');
    }

    /**
     * Memproses login.
     */
    public function login(Request $request)
    {
        $validated = $request->validate(
            [
                'username' => [
                    'required',
                    'string',
                    'max:150',
                ],
                'password' => [
                    'required',
                    'string',
                ],
            ],
            [
                'username.required' => 'Username atau email wajib diisi.',
                'password.required' => 'Password wajib diisi.',
            ]
        );

        $username = trim($validated['username']);

        $user = DB::table('users')
            ->where('status', 'aktif')
            ->where(function ($query) use ($username) {
                $query
                    ->where('nama', $username)
                    ->orWhere('email', $username);
            })
            ->first();

        if (
            !$user ||
            !Hash::check($validated['password'], $user->password)
        ) {
            return back()
                ->withInput($request->only('username'))
                ->with('error', 'Username atau password salah.');
        }

        // Mencegah session fixation.
        $request->session()->regenerate();

        $request->session()->put('user', $user);

        return match ($user->role) {
            'admin' => redirect()->route('dashboard'),
            'auditee' => redirect()->route('dashboard.auditee'),
            'auditor' => redirect()->route('dashboard.auditor'),
            default => $this->logoutInvalidRole($request),
        };
    }

    /**
     * Memproses logout.
     */
    public function logout(Request $request)
    {
        $request->session()->forget('user');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('landing')
            ->with('success', 'Anda berhasil logout.');
    }

    /**
     * Menangani role yang tidak valid.
     */
    private function logoutInvalidRole(Request $request)
    {
        $request->session()->forget('user');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('error', 'Role pengguna tidak valid.');
    }
}