<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
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
            Permission::updateOrCreate(
                ['name' => $permission],
                ['name' => $permission]
            );
        }
        $this->createRoles();
        $this->createAdmin();
        $this->createDoctor();
        $this->createReceptionist();
    }

    private function createRoles()
    {
        foreach (RoleEnum::cases() as $role) {
            Role::updateOrCreate(
                ['name' => $role->code()],
                ['name' => $role->code()]
            );
        }
    }

    private function createReceptionist()
    {
        $roleReceptionist = Role::where('name', RoleEnum::RECEPTIONIST->code())->first();
        if ($roleReceptionist) {
            $roleReceptionist->givePermissionTo([
                Permissions::READ_ORDERS,
                Permissions::WRITE_ORDERS
            ]);
        }
    }

    private function createDoctor()
    {
        $roleDoctor = Role::where('name', RoleEnum::DOCTOR->code())->first();
        if ($roleDoctor) {
            $roleDoctor->givePermissionTo([
                Permissions::READ_PATIENTS,
                Permissions::WRITE_PATIENTS,
            ]);
        }
    }

    private function createAdmin()
    {
        $roleAdmin = Role::where('name', RoleEnum::ADMINISTRATOR->code())->first();
        if ($roleAdmin) {
            $roleAdmin->givePermissionTo(Permission::all());
        }
        $userAdmin = User::updateOrCreate(
            ['email' => 'admin@email.com'],
            ['name' => 'Admin', 'password' => Hash::make('admin')]
        );
        $userAdmin->assignRole($roleAdmin);
    }
}
