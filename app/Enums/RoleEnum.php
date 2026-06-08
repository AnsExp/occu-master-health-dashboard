<?php

namespace App\Enums;

enum RoleEnum
{
    case DOCTOR;
    case RECEPTIONIST;
    case ADMINISTRATOR;
    case ORDER_READER;

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
            self::ORDER_READER => 'Order Reader',
            default => '',
        };
    }

    public static function fromCode(string $code): ?self
    {
        return match ($code) {
            'doctor' => self::DOCTOR,
            'receptionist' => self::RECEPTIONIST,
            'administrator' => self::ADMINISTRATOR,
            'order_reader' => self::ORDER_READER,
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
