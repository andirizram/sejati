<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserControllerBu extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'menu']);
    }

    public function daftar(): Response
    {
        $users = User::with('roles.menus')->get();
        $roles = $users->pluck('roles')->flatten(1);

        return response()->view('pengelolaan_akun', compact('users', 'roles'));
    }


}
