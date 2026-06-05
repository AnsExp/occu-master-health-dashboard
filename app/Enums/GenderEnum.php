<?php

namespace App\Enums;

enum GenderEnum
{
    case MALE;
    case FEMALE;
    case OTHER;

    public function code(): string
    {
        return strtolower($this->name);
    }

    public function label(): string
    {
        return match ($this) {
            self::MALE => 'Masculino',
            self::FEMALE => 'Femenino',
            self::OTHER => 'Otro',
        };
    }

    public static function fromCode(string $code): ?self
    {
        return match ($code) {
            'male' => self::MALE,
            'female' => self::FEMALE,
            'other' => self::OTHER,
            default => null,
        };
    }
}