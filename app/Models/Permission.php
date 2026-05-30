<?php

namespace App\Models;

class Permission
{
    public const READ_PATIENTS = 'read.patients';
    public const WRITE_PATIENTS = 'write.patients';
    public const READ_DOCTORS = 'read.doctors';
    public const WRITE_DOCTORS = 'write.doctors';
    public const READ_ORDERS = 'read.orders';
    public const WRITE_ORDERS = 'write.orders';
    public const READ_USERS = 'read.users';
    public const WRITE_USERS = 'write.users';
    public const READ_OPHTALMOLOGY = 'read.ophtalmology';
    public const WRITE_OPHTALMOLOGY = 'write.ophtalmology';
    public const READ_AUDIOLOGY = 'read.audiology';
    public const WRITE_AUDIOLOGY = 'write.audiology';
    public const READ_OCCUPATIONAL = 'read.occupational';
    public const WRITE_OCCUPATIONAL = 'write.occupational';

    public static function all()
    {
        $constants = (new \ReflectionClass(self::class))->getConstants();
        return array_values($constants);
    }

    public static function has(string $permission): bool
    {
        if (auth()->check()) {
            return auth()->user()->can($permission);
        }
        return false;
    }
}
