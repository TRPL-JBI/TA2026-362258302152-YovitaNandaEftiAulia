<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    /*
    |--------------------------------------------------------------------------
    | KHUSUS ADMIN
    |--------------------------------------------------------------------------
    */

    public function handle(
        Request $request,
        Closure $next
    ): Response {
        /*
        |--------------------------------------------------------------------------
        | AMBIL USER LANGSUNG DARI DATABASE
        |--------------------------------------------------------------------------
        */

        $user = User::find(
            $request->session()->get('user_id')
        );

        /*
        |--------------------------------------------------------------------------
        | PERIKSA USER DAN STATUS
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $user &&
            $user->status === 'aktif',
            403,
            'Akun tidak ditemukan atau sudah dinonaktifkan.'
        );

        /*
        |--------------------------------------------------------------------------
        | PERIKSA ROLE
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $user->role === 'admin',
            403,
            'Halaman ini hanya dapat diakses oleh admin.'
        );

        /*
        |--------------------------------------------------------------------------
        | BAGIKAN USER KE REQUEST DAN VIEW
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