<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditorOnly
{
    /**
     * Memastikan pengguna yang masuk adalah Auditor aktif.
     */
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $userId = $request->session()->get('user_id');

        if (!$userId) {
            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Sesi login tidak ditemukan. Silakan login kembali.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA USER TERBARU DARI DATABASE
        |--------------------------------------------------------------------------
        */

        $user = User::query()->find($userId);

        if (!$user) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Data pengguna tidak ditemukan. Silakan login kembali.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | CEK STATUS USER
        |--------------------------------------------------------------------------
        */

        $status = strtolower(
            trim((string) $user->status)
        );

        if ($status !== 'aktif') {
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Akun Anda sudah dinonaktifkan oleh admin.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | CEK ROLE AUDITOR
        |--------------------------------------------------------------------------
        */

        $role = strtolower(
            trim((string) $user->role)
        );

        abort_unless(
            $role === 'auditor',
            403,
            'Halaman ini hanya dapat diakses oleh Auditor.'
        );

        /*
        |--------------------------------------------------------------------------
        | KIRIM USER KE REQUEST DAN VIEW
        |--------------------------------------------------------------------------
        */

        $request->attributes->set(
            'auth_user',
            $user
        );

        view()->share(
            'authUser',
            $user
        );

        return $next($request);
    }
}