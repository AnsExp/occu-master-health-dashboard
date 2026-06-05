<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'price',
        'periodicity',
        'description',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(PlanDetail::class);
    }
}
