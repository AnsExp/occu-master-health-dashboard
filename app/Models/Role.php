<?php

namespace App\Models;

class Role
{
    public const DOCTOR = 'Médico';
    public const RECEPTIONIST = 'Recepcionista';
    public const ADMINISTRATOR = 'Administrador';

    public static function all()
    {
        $constants = (new \ReflectionClass(self::class))->getConstants();
        return array_values($constants);
    }
}
