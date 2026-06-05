<?php

namespace App\Models;

class Permission
{
    public const READ_PATIENTS = 'read.patients';
    public const WRITE_PATIENTS = 'write.patients';
    public const ERASE_PATIENTS = 'erase.patients';
    public const READ_DOCTORS = 'read.doctors';
    public const WRITE_DOCTORS = 'write.doctors';
    public const ERASE_DOCTORS = 'erase.doctors';
    public const READ_PLANS = 'read.plans';
    public const WRITE_PLANS = 'write.plans';
    public const ERASE_PLANS = 'erase.plans';
    public const READ_ORDERS = 'read.orders';
    public const WRITE_ORDERS = 'write.orders';
    public const ERASE_ORDERS = 'erase.orders';
    public const READ_USERS = 'read.users';
    public const WRITE_USERS = 'write.users';
    public const ERASE_USERS = 'erase.users';
    public const READ_OPHTHALMOLOGY = 'read.ophthalmology';
    public const WRITE_OPHTHALMOLOGY = 'write.ophthalmology';
    public const ERASE_OPHTHALMOLOGY = 'erase.ophthalmology';
    public const READ_AUDIOLOGY = 'read.audiology';
    public const WRITE_AUDIOLOGY = 'write.audiology';
    public const ERASE_AUDIOLOGY = 'erase.audiology';
    public const READ_OCCUPATIONAL = 'read.occupational';
    public const WRITE_OCCUPATIONAL = 'write.occupational';
    public const ERASE_OCCUPATIONAL = 'erase.occupational';

    public static function all()
    {
        $constants = (new \ReflectionClass(self::class))->getConstants();
        return array_values($constants);
    }

    /**
     * @param string|string[] $permission
     * @return bool
     */
    public static function has($permission): bool
    {
        if (auth()->check()) {
            if (is_string($permission)) {
                $permission = [$permission];
            }
            foreach ($permission as $perm) {
                if(auth()->user()->can($perm)){
                    return true;
                }
            }
        }
        return false;
    }
}
