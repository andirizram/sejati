<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $except = [
            'login',
            'logout',
            'verification.verify',
            'verification.notice',
            'verification.resend',
            'verification.send',
            'verification.verify',
            'welcome',
            'sanctum.csrf-cookie',
            'ignition.healthcheck',
            'ignition.executesolution',
            'ignition.updateconfig',
        ];

        $routeCollection = Route::getRoutes()->get();
        foreach ($routeCollection as $item) {
            $name = $item->action;
            if (!empty($name['as'])) {
                $permission = $name['as'];
                $permission = trim(strtolower($permission));

                if (Permission::where('name', $permission)->exists()) {
                    continue;
                }

                if (in_array($permission, $except)) {
                    continue;
                }

                Permission::create([
                    'name' => $permission,
                    'guard_name' => 'web'
                ]);
            }
        }

    }
}
