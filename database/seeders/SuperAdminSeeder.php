<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        User::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $permission = Permission::firstOrCreate(
            ['name' => 'impersonate user'],
            ['guard_name' => 'web']
        );

        $role = Role::firstOrCreate(
            ['name' => 'God Admin'],
            ['guard_name' => 'web']
        );

        if (!$role->hasPermissionTo('impersonate user')) {
            $role->givePermissionTo($permission);
        }

        $user = User::firstOrCreate(
            ['email' => 'GodAdmin@example.com'],
            [
                'name' => 'God Admin',
                'password' => bcrypt('123456'),
            ]
        );

        if (!$user->hasRole('God Admin')) {
            $user->assignRole($role);
        }

        $permissions = Permission::all();

        $role->syncPermissions($permissions);
    }
}
