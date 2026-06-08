<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\ActionEnum;
use App\Enums\TableEnum;
use App\Http\Controllers\AuditoryController;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;
    // The User model requires this trait
    use HasRoles;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    protected static function booted()
    {
        static::created(function ($user) {
            AuditoryController::info(
                TableEnum::USERS,
                ActionEnum::INSERT,
                $user->id,
                new_data: $user->toArray()
            );
        });
        static::updating(function ($user) {
            AuditoryController::info(
                TableEnum::USERS,
                ActionEnum::UPDATE,
                $user->id,
                old_data: $user->getOriginal(),
                new_data: $user->getDirty()
            );
        });
        static::deleted(function ($user) {
            AuditoryController::info(
                TableEnum::USERS,
                ActionEnum::DELETE,
                $user->id,
                old_data: $user->toArray()
            );
        });
    }
}
