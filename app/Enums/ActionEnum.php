<?php

namespace App\Enums;

enum ActionEnum
{
    case INSERT;
    case UPDATE;
    case DELETE;

    public function code(): string
    {
        return strtolower($this->name);
    }

    public function label(): string
    {
        return match ($this) {
            self::INSERT => 'Insertar',
            self::UPDATE => 'Actualizar',
            self::DELETE => 'Eliminar',
        };
    }

    public static function fromCode(string $code): ?self
    {
        return match ($code) {
            'insert' => self::INSERT,
            'update' => self::UPDATE,
            'delete' => self::DELETE,
            default => null,
        };
    }
}