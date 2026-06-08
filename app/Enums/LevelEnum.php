<?php

namespace App\Enums;

enum LevelEnum
{
    case INFO;
    case WARNING;
    case ERROR;
    case CRITICAL;

    public function code(): string
    {
        return strtolower($this->name);
    }

    public function label(): string
    {
        return match ($this) {
            self::INFO => 'Info',
            self::WARNING => 'Advertencia',
            self::ERROR => 'Error',
            self::CRITICAL => 'Crítico',
        };
    }

    public static function fromCode(string $code): ?self
    {
        return match ($code) {
            'info' => self::INFO,
            'warning' => self::WARNING,
            'error' => self::ERROR,
            'critical' => self::CRITICAL,
            default => null,
        };
    }
}