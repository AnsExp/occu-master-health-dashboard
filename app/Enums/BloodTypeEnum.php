<?php

namespace App\Enums;

enum BloodTypeEnum
{
    case A_POSITIVE;
    case A_NEGATIVE;
    case B_POSITIVE;
    case B_NEGATIVE;
    case AB_POSITIVE;
    case AB_NEGATIVE;
    case O_POSITIVE;
    case O_NEGATIVE;

    public function code(): string
    {
        return strtolower($this->name);
    }

    public function label(): string
    {
        return match ($this) {
            self::A_POSITIVE => 'A+',
            self::A_NEGATIVE => 'A-',
            self::B_POSITIVE => 'B+',
            self::B_NEGATIVE => 'B-',
            self::AB_POSITIVE => 'AB+',
            self::AB_NEGATIVE => 'AB-',
            self::O_POSITIVE => 'O+',
            self::O_NEGATIVE => 'O-',
            default => '',
        };
    }

    public static function fromCode(string $code): ?self
    {
        return match ($code) {
            'a_positive' => self::A_POSITIVE,
            'a_negative' => self::A_NEGATIVE,
            'b_positive' => self::B_POSITIVE,
            'b_negative' => self::B_NEGATIVE,
            'ab_positive' => self::AB_POSITIVE,
            'ab_negative' => self::AB_NEGATIVE,
            'o_positive' => self::O_POSITIVE,
            'o_negative' => self::O_NEGATIVE,
            default => null,
        };
    }
}