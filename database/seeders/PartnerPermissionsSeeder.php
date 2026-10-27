<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PartnerPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = collect([
            'lihat kategori-partner',
            'buat kategori-partner',
            'edit kategori-partner',
            'hapus kategori-partner',
            'lihat partner',
            'buat partner',
            'edit partner',
            'hapus partner',
            'edit label partner',
        ])->map(fn (string $name) => Permission::firstOrCreate([
            'name' => $name,
            'guard_name' => 'web',
        ]));

        $role = Role::where('name', 'God Admin')->where('guard_name', 'web')->first();
        $role?->givePermissionTo($permissions);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
