<?php

namespace App\Models;

enum Periodicity
{
    case Weekly;
    case Monthly;
    case Yearly;

    public function label(): string
    {
        return match ($this) {
            self::Weekly => 'Semanal',
            self::Monthly => 'Mensual',
            self::Yearly => 'Anual',
        };
    }
}