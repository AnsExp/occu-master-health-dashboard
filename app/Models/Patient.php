<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'nationality',
        'gender',
        'birth_date',
        'id_card',
        'email',
        'phone',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function metadata(): HasMany
    {
        return $this->hasMany(Metadata::class, 'meta_id')->where('meta_type', 'patient');
    }
}
