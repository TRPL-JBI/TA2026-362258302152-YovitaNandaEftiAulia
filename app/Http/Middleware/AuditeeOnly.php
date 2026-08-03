<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditeeOnly
{
    /**
     * Memastikan pengguna yang masuk adalah Auditee aktif.
     */
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        /*
        |--------------------------------------------------------------------------
        | AMBIL ID USER DARI SESSION
        |--------------------------------------------------------------------------
        |
        | Session hanya menyimpan user_id, bukan seluruh object user.
        |
        */

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
        | AMBIL USER TERBARU DARI DATABASE
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
                    'Data pengguna tidak ditemukan.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | CEK STATUS USER
        |--------------------------------------------------------------------------
        */

        $status = strtolower(
            trim(
                (string) $user->status
            )
        );

        if ($status !== 'aktif') {
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Akun sudah dinonaktifkan.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | CEK ROLE AUDITEE
        |--------------------------------------------------------------------------
        */

        $role = strtolower(
            trim(
                (string) $user->role
            )
        );

        if ($role !== 'auditee') {
            abort(
                403,
                'Halaman ini hanya dapat diakses oleh Auditee.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | KIRIM USER KE CONTROLLER
        |--------------------------------------------------------------------------
        |
        | Controller dapat mengambil user melalui:
        |
        | request()->attributes->get('auth_user')
        |
        */

        $request->attributes->set(
            'auth_user',
            $user
        );

        return $next($request);
    }
}