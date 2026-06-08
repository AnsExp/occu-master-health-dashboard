<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PatientPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(PermissionEnum::VIEW_PATIENTS->code());
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Patient $patient): bool
    {
        return $user->can(PermissionEnum::VIEW_PATIENTS->code());
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(PermissionEnum::STORE_PATIENTS->code());
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Patient $patient): bool
    {
        return $user->can(PermissionEnum::UPDATE_PATIENTS->code());
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Patient $patient): bool
    {
        return $user->can(PermissionEnum::DESTROY_PATIENTS->code());
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Patient $patient): bool
    {
        return $user->can(PermissionEnum::UPDATE_PATIENTS->code());
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Patient $patient): bool
    {
        return $user->can(PermissionEnum::DESTROY_PATIENTS->code());
    }
}
