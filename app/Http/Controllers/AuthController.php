<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

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

        if ($user && $request->password == $user->password) {
            Session::put('user', $user);

            return redirect()->route('unit-kerja.index');
        }

        return back()->with('error', 'Username atau password salah');
    }

    public function logout()
    {
        Session::forget('user');
        return redirect()->route('login');
    }
}