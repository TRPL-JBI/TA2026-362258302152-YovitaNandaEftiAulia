<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class CheckSession
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        /*
        |--------------------------------------------------------------------------
        | AMBIL USER ID DARI SESSION
        |--------------------------------------------------------------------------
        */

        $userId = (int) $request
            ->session()
            ->get('user_id', 0);

        /*
        |--------------------------------------------------------------------------
        | SESSION LOGIN TIDAK DITEMUKAN
        |--------------------------------------------------------------------------
        */

        if ($userId <= 0) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'email' =>
                        'Silakan login terlebih dahulu.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA USER TERBARU DARI DATABASE
        |--------------------------------------------------------------------------
        */

        $user = User::with('unit')
            ->find($userId);

        /*
        |--------------------------------------------------------------------------
        | USER SUDAH DIHAPUS
        |--------------------------------------------------------------------------
        */

        if (!$user) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' =>
                        'Data pengguna tidak ditemukan. Silakan login kembali.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | PERIKSA STATUS TERBARU
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
                ->withErrors([
                    'email' =>
                        'Akun Anda sudah dinonaktifkan oleh admin.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN USER HANYA PADA REQUEST
        |--------------------------------------------------------------------------
        */

        $request->attributes->set(
            'auth_user',
            $user
        );

        /*
        |--------------------------------------------------------------------------
        | BAGIKAN USER KE SELURUH BLADE
        |--------------------------------------------------------------------------
        */

        View::share(
            'authUser',
            $user
        );

        return $next($request);
    }
}