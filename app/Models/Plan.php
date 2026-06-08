<?php

namespace App\Models;

use App\Enums\ActionEnum;
use App\Enums\TableEnum;
use App\Http\Controllers\AuditoryController;
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

    protected static function booted()
    {
        static::created(function ($plan) {
            AuditoryController::info(
                TableEnum::PLANS,
                ActionEnum::INSERT,
                $plan->id,
                new_data: $plan->toArray()
            );
        });
        static::updating(function ($plan) {
            AuditoryController::info(
                TableEnum::PLANS,
                ActionEnum::UPDATE,
                $plan->id,
                old_data: $plan->getOriginal(),
                new_data: $plan->getDirty()
            );
        });
        static::deleted(function ($plan) {
            AuditoryController::info(
                TableEnum::PLANS,
                ActionEnum::DELETE,
                $plan->id,
                old_data: $plan->toArray()
            );
        });
    }
}
