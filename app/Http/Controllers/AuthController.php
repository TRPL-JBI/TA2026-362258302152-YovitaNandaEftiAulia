<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    $user = DB::table('users')
        ->where('nama', $request->username)
        ->where('status', 'aktif')
        ->first();

    if ($user && Hash::check($request->password, $user->password)) {

        Session::put('user', $user);

        if ($user->role == 'admin') {
            return redirect()->route('dashboard');
        }

        if ($user->role == 'auditee') {
            return redirect()->route('dashboard.auditee');
        }

        if ($user->role == 'auditor') {
            return redirect()->route('dashboard.auditor');
        }

    }

    return back()->with(
        'error',
        'Username atau password salah'
    );
}
    public function logout()
    {
        Session::forget('user');
        return redirect()->route('login');
    }
}