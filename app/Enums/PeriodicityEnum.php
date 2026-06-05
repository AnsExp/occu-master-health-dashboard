<?php

namespace App\Enums;

enum PeriodicityEnum
{
    case WEEKLY;
    case MONTHLY;
    case YEARLY;

    public function code(): string
    {
        return strtolower($this->name);
    }

    public function label(): string
    {
        return match ($this) {
            self::WEEKLY => 'Semanal',
            self::MONTHLY => 'Mensual',
            self::YEARLY => 'Anual',
        };
    }

    public static function fromCode(string $code): ?self
    {
        return match ($code) {
            'weekly' => self::WEEKLY,
            'monthly' => self::MONTHLY,
            'yearly' => self::YEARLY,
            default => null,
        };
    }
}