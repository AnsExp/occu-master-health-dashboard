<?php

namespace App\Http\Services;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Enums\SpecialtyEnum;
use App\Models\Doctor;
use App\Models\User;
use DB;

class UserStoreService
{
    public function store(array $data)
    {
        $saved = false;
        DB::transaction(function () use ($data, &$saved) {
            $user = new User($data);
            $user->password = bcrypt($data['password']);
            $user->assignRole($data['role']);
            $userSaved = $user->save();
            if ($userSaved && $data['role'] !== RoleEnum::DOCTOR->code()) {
                $doctor = new Doctor($data);
                $doctor->first_name = $user->name;
                $doctor->user()->associate($user);
                $doctorSaved = $doctor->save();
                if ($doctorSaved) {
                    switch ($data['specialty']) {
                        case SpecialtyEnum::AUDIOLOGY->code():
                            $user->givePermissionTo([
                                PermissionEnum::VIEW_AUDIOLOGY->code(),
                                PermissionEnum::STORE_AUDIOLOGY->code()
                            ]);
                            break;
                        case SpecialtyEnum::OCCUPATIONAL->code():
                            $user->givePermissionTo([
                                PermissionEnum::VIEW_OCCUPATIONAL->code(),
                                PermissionEnum::STORE_OCCUPATIONAL->code()
                            ]);
                            break;
                        case SpecialtyEnum::OPHTHALMOLOGY->code():
                            $user->givePermissionTo([
                                PermissionEnum::VIEW_OPHTHALMOLOGY->code(),
                                PermissionEnum::STORE_OPHTHALMOLOGY->code()
                            ]);
                            break;
                    }
                }
            }
            $saved = $userSaved && ($data['role'] !== RoleEnum::DOCTOR->code() || $doctorSaved);
        });
        return $saved;
    }
}