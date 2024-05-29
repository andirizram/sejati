<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'Administrator',
            'Kepala Program Studi',
            'Dosen',
            'Mahasiswa',
        ];

        DB::transaction(function () use ($roles): void {
            foreach ($roles as $role) {
                $created_role = Role::create([
                    'name' => $role,
                    'guard_name' => 'web',
                ]);

                switch ($role) {
                    case 'Administrator':
                        $created_role->givePermissionTo(Permission::all());
                        break;
                    case 'Kepala Program Studi':
                        $created_role->givePermissionTo([
                            'jadwal-ta.index',
                            'jadwal-ta.show',
                            'jadwal-ta.store',
                            'jadwal-ta.destroy',
                            'jadwal-tpb.index',
                            'jadwal-tpb.show',
                            'jadwal-tpb.store',
                            'jadwal-tpb.destroy',
                            'jadwal-prodi.index',
                            'jadwal-prodi.show',
                            'jadwal-prodi.store',
                            'jadwal-prodi.destroy',
                            'jadwal-lain.index',
                            'jadwal-lain.show',
                            'jadwal-lain.store',
                            'jadwal-lain.destroy',
                            'jadwal.export',
                        ]);
                        break;
                    case 'Dosen':
                        $created_role->givePermissionTo([
                            'jadwal-saya',
                            'jadwal-ta.index',
                            'jadwal-tpb.index',
                            'jadwal-prodi.index',
                            'jadwal-lain.index'
                        ]);
                        break;
                    case 'Mahasiswa':
                        $created_role->givePermissionTo([
                            'jadwal-saya',
                            'jadwal.ambil',
                            'jadwal-ta.index',
                            'jadwal-tpb.index',
                            'jadwal-prodi.index',
                            'jadwal-lain.index'
                        ]);
                        break;
                }
                if ($role == 'Administrator') {
                    $created_role->givePermissionTo(Permission::all());
                }

                if ($role == 'Mahasiswa') {
                    $created_role->givePermissionTo([
                        'jadwal-saya',
                        'jadwal.ambil',
                        'jadwal-ta.index',
                        'jadwal-tpb.index',
                        'jadwal-prodi.index',
                        'jadwal-lain.index'

                    ]);
                }
            }
        });
    }
}
