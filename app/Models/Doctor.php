<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doctor extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'id_card',
        'specialty',
        'phone',
        'email',
    ];

    public function metadata(): HasMany
    {
        return $this->hasMany(Metadata::class, 'meta_id')->where('meta_type', 'doctor');
    }
}
