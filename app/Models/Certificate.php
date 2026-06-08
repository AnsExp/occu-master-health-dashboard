<?php

namespace App\Models;

use App\Enums\ActionEnum;
use App\Enums\TableEnum;
use App\Http\Controllers\AuditoryController;
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
        'certificate_number',
        'content',
    ];

    protected function casts(): array
    {
        return [
            'content' => 'array',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function metadata(): HasMany
    {
        return $this->hasMany(Metadata::class, 'meta_id')->where('meta_type', 'certificate');
    }

    protected static function booted()
    {
        static::created(function ($certificate) {
            AuditoryController::info(
                TableEnum::CERTIFICATES,
                ActionEnum::INSERT,
                $certificate->id,
                new_data: $certificate->toArray()
            );
        });
        static::updating(function ($certificate) {
            AuditoryController::info(
                TableEnum::CERTIFICATES,
                ActionEnum::UPDATE,
                $certificate->id,
                old_data: $certificate->getOriginal(),
                new_data: $certificate->getDirty()
            );
        });
        static::deleted(function ($certificate) {
            AuditoryController::info(
                TableEnum::CERTIFICATES,
                ActionEnum::DELETE,
                $certificate->id,
                old_data: $certificate->toArray()
            );
        });
    }

    public static function generate_number()
    {
        $number = str_pad((string) mt_rand(0, 9999999), 7, '0', STR_PAD_LEFT);

        $exists = Certificate::where('certificate_number', $number)->exists();
        if ($exists) {
            return self::generate_number();
        }
        return $number;
    }
}
