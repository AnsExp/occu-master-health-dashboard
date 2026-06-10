<?php

namespace App\Rules;

use App\Models\Certificate;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueCertificateTypePerOrder implements ValidationRule
{
    public function __construct(private readonly string $certificateType)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null || $value === '') {
            return;
        }

        $exists = Certificate::query()
            ->where('order_id', $value)
            ->where('type', $this->certificateType)
            ->exists();

        if ($exists) {
            $fail('Ya existe un certificado de este tipo para la orden seleccionada.');
        }
    }
}
