<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
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
        $this->createUsers();
        $this->createRoles();
        $this->createPermissions();
        $this->assignPermissionsToRoles();
        $this->assignRolesToUsers();
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

    private function createPermissions()
    {
        foreach (PermissionEnum::cases() as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission->code()],
                ['name' => $permission->code()]
            );
        }
    }

    private function createUsers()
    {
        User::updateOrCreate(
            ['email' => 'admin@email.com'],
            ['name' => 'Admin', 'password' => Hash::make('admin')]
        );
        User::updateOrCreate(
            ['email' => 'order_reader'],
            ['name' => 'Order Reader', 'password' => Hash::make('order_reader')]
        );
    }

    public function assignPermissionsToRoles()
    {
        $permissions = Permission::all();
        foreach (RoleEnum::cases() as $role) {
            $roleModel = Role::where('name', $role->code())->first();
            if ($roleModel) {
                switch ($role) {
                    case RoleEnum::ADMINISTRATOR:
                        $roleModel->syncPermissions($permissions);
                        break;
                    case RoleEnum::DOCTOR:
                        $roleModel->syncPermissions($permissions->whereIn('name', [
                            PermissionEnum::VIEW_PATIENTS->code(),
                            PermissionEnum::STORE_PATIENTS->code(),
                            PermissionEnum::UPDATE_PATIENTS->code(),
                        ]));
                        break;
                    case RoleEnum::RECEPTIONIST:
                        $roleModel->syncPermissions($permissions->whereIn('name', [
                            PermissionEnum::VIEW_ORDERS->code(),
                            PermissionEnum::STORE_ORDERS->code()
                        ]));
                        break;
                    case RoleEnum::ORDER_READER:
                        $roleModel->syncPermissions($permissions->whereIn('name', [
                            PermissionEnum::VIEW_ORDERS->code()
                        ]));
                        break;
                }
            }
        }
    }

    public function assignRolesToUsers()
    {
        foreach (User::all() as $user) {
            $userModel = User::where('email', $user->email)->first();
            if ($userModel) {
                switch ($user->email) {
                    case 'admin@email.com':
                        $roleModel = Role::where('name', RoleEnum::ADMINISTRATOR->code())->first();
                        $userModel->syncRoles($roleModel);
                        break;
                    case 'order_reader':
                        $roleModel = Role::where('name', RoleEnum::ORDER_READER->code())->first();
                        $userModel->syncRoles($roleModel);
                        break;
                }
            }
        }
    }
}
