<?php

namespace App\Http\Controllers;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->only(['formLogin', 'login']);
        $this->middleware('auth')->only(['logout']);
    }

    public function formLogin()
    {
        return response()->view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $menuAwal = Auth::user()->roles()
                ->first()
                ->menus()
                ->first()
                ->nama_unik();

            return redirect()->intended(route($menuAwal));
        }

        return back()->withErrors([
            'email' => 'User tidak ditemukan.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('welcome');
    }
}
