<?php

namespace Database\Seeders;

use App\Models\Permission as Permissions;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach (Permissions::all() as $permission) {
            Permission::create(['name' => $permission]);
        }
        $this->createAdmin();
        $this->createDoctor();
        $this->createReceptionist();
    }

    private function createReceptionist()
    {
        $roleReceptionist = Role::create(['name' => \App\Models\Role::RECEPTIONIST]);
        $roleReceptionist->givePermissionTo([
            Permissions::READ_ORDERS,
            Permissions::WRITE_ORDERS
        ]);
    }

    private function createDoctor()
    {
        $roleDoctor = Role::create(['name' => \App\Models\Role::DOCTOR]);
        $roleDoctor->givePermissionTo([
            Permissions::READ_PATIENTS,
            Permissions::WRITE_PATIENTS,
            Permissions::READ_ORDERS,
            Permissions::WRITE_ORDERS
        ]);
    }

    private function createAdmin()
    {
        $roleAdmin = Role::create(['name' => \App\Models\Role::ADMINISTRATOR]);
        $roleAdmin->givePermissionTo(Permission::all());
        $userAdmin = User::create([
            'email' => 'admin@email.com',
            'name' => 'Admin',
            'password' => Hash::make('admin')
        ]);
        $userAdmin->assignRole($roleAdmin);
    }
}
