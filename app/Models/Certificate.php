<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Certificate extends Model
{
    protected $fillable = [
        'title',
        'type',
        'order_id',
        'doctor_id',
        'patient_id',
        'content',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function metadata(): HasMany
    {
        return $this->hasMany(Metadata::class, 'meta_id')->where('meta_type', 'certificate');
    }
}
