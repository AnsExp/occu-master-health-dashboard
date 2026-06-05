<?php

namespace App\Enums;

enum RoleEnum
{
    case DOCTOR;
    case RECEPTIONIST;
    case ADMINISTRATOR;

    public function code(): string
    {
        return strtolower($this->name);
    }

    public function label(): string
    {
        return match ($this) {
            self::DOCTOR => 'Doctor',
            self::RECEPTIONIST => 'Recepcionista',
            self::ADMINISTRATOR => 'Administrador',
            default => '',
        };
    }

    public static function fromCode(string $code): ?self
    {
        return match ($code) {
            'doctor' => self::DOCTOR,
            'receptionist' => self::RECEPTIONIST,
            'administrator' => self::ADMINISTRATOR,
            default => null,
        };
    }

    public static function has(RoleEnum $role): bool
    {
        if (auth()->check()) {
            return auth()->user()->hasRole($role->code());
        }
        return false;
    }
}
