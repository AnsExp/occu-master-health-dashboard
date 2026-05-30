<?php

namespace App\Models;

enum Gender
{
    case Male;
    case Female;
    case Other;

    public function label(): string
    {
        return match ($this) {
            self::Male => 'Masculino',
            self::Female => 'Femenino',
            self::Other => 'Otro',
        };
    }

    public static function fromString(string $value): self
    {
        $sanitized = strtolower(trim($value));
        return match ($sanitized) {
            'male' => self::Male,
            'female' => self::Female,
            'other' => self::Other,
            default => throw new \InvalidArgumentException("Invalid gender: $value"),
        };
    }
}