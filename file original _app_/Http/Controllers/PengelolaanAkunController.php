<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PengelolaanAkunController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $users = User::get();
        $roles = Role::get();
        $permissions = Permission::get();
        return view('pengelolaan_akun.index', compact('users', 'roles', 'permissions'));
    }
}
