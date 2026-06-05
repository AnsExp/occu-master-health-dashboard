<?php

namespace App\Enums;

enum DocumentEnum
{
    case ORDER;
    case AUDIOLOGY;
    case OCCUPATIONAL;
    case OPHTHALMOLOGY;

    public function code(): string
    {
        return strtolower($this->name);
    }

    public function label(): string
    {
        return match ($this) {
            self::ORDER => 'Orden',
            self::AUDIOLOGY => 'Audiología',
            self::OCCUPATIONAL => 'Medicina Ocupacional',
            self::OPHTHALMOLOGY => 'Oftalmología',
            default => '',
        };
    }

    public static function fromCode(string $code): ?self
    {
        return match ($code) {
            'order' => self::ORDER,
            'audiology' => self::AUDIOLOGY,
            'occupational' => self::OCCUPATIONAL,
            'ophthalmology' => self::OPHTHALMOLOGY,
            default => null,
        };
    }
}