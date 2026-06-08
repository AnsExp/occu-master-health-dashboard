<?php

namespace App\Enums;

enum TableEnum
{
    case PLANS;
    case USERS;
    case ORDERS;
    case DOCTORS;
    case PATIENTS;
    case CERTIFICATES;

    public function code(): string
    {
        return strtolower($this->name);
    }

    public function label(): string
    {
        return match ($this) {
            self::PLANS => 'Planes',
            self::USERS => 'Usuarios',
            self::ORDERS => 'Órdenes',
            self::DOCTORS => 'Doctores',
            self::PATIENTS => 'Pacientes',
            self::CERTIFICATES => 'Certificados',
        };
    }

    public static function fromCode(string $code): ?self
    {
        return match ($code) {
            'plans' => self::PLANS,
            'users' => self::USERS,
            'orders' => self::ORDERS,
            'doctors' => self::DOCTORS,
            'patients' => self::PATIENTS,
            'certificates' => self::CERTIFICATES,
            default => null,
        };
    }
}