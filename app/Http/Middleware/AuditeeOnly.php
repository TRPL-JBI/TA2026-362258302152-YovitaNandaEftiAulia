<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditeeOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('user')) {
            return redirect('/login');
        }

        $user = session('user');

        $role = is_array($user)
            ? $user['role']
            : $user->role;

        if ($role !== 'auditee') {
            abort(403, 'Anda tidak memiliki hak akses.');
        }

        return $next($request);
    }
}