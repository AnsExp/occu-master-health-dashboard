<?php

namespace App\Models;

enum Specialty
{
    case CARDIOLOGY;
    case DERMATOLOGY;
    case NEUROLOGY;
    case PEDIATRICS;

    public function label(): string
    {
        return match ($this) {
            self::CARDIOLOGY => 'Cardiología',
            self::DERMATOLOGY => 'Dermatología',
            self::NEUROLOGY => 'Neurología',
            self::PEDIATRICS => 'Pediatría',
            default => 'Desconocida',
        };
    }
}