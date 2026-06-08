<?php

namespace App\Models;

use App\Enums\ActionEnum;
use App\Enums\TableEnum;
use App\Http\Controllers\AuditoryController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public static $STACK = [
        [
            'name' => 'ELECTROCARDIOGRAMA',
            'price' => 25,
        ],
        [
            'name' => 'BIOMETRIA COSTA',
            'price' => 7,
        ],
        [
            'name' => 'RX TORAX STANDAR AP',
            'price' => 30,
        ],
        [
            'name' => 'TRIGLICERIDOS',
            'price' => 2.9,
        ],
        [
            'name' => 'INSUMOS CARNET DE TIPIFICACION',
            'price' => 1.71,
        ],
        [
            'name' => 'CONSULTA PSICOLOGIA',
            'price' => 20,
        ],
        [
            'name' => 'CONSULTA MEDICINA GENERAL',
            'price' => 15,
        ],
        [
            'name' => 'CONSULTA OPTOMETRICA',
            'price' => 15,
        ],
        [
            'name' => 'DIAGNOSTICO ODONTOLOGICO',
            'price' => 20,
        ],
        [
            'name' => 'COLESTEROL',
            'price' => 3.5,
        ],
        [
            'name' => 'AUDIOMETRIA',
            'price' => 20,
        ],
        [
            'name' => 'EMO',
            'price' => 4,
        ],
        [
            'name' => 'TIPIFICACION SANGUINEA',
            'price' => 5.03,
        ],
        [
            'name' => 'TGO/ASAT',
            'price' => 3.9,
        ],
        [
            'name' => 'UREA / BUN',
            'price' => 2.8,
        ],
        [
            'name' => 'TGP/ALAT',
            'price' => 3.9,
        ],
        [
            'name' => 'COPROPARASITARIO',
            'price' => 3.5,
        ],
        [
            'name' => 'CREATININA',
            'price' => 3.6,
        ],
        [
            'name' => 'HIV 1 2',
            'price' => 11,
        ],
        [
            'name' => 'ACIDO URICO',
            'price' => 3,
        ],
        [
            'name' => 'GLUCOSA BASAL',
            'price' => 2.9,
        ],
        [
            'name' => 'VDRL',
            'price' => 6,
        ],
    ];

    protected $fillable = [
        'patient_id',
        'order_number',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public static function generate_number()
    {
        $number = str_pad((string) mt_rand(0, 9999999), 7, '0', STR_PAD_LEFT);

        $exists = Order::where('order_number', $number)->exists();
        if ($exists) {
            return self::generate_number();
        }
        return $number;
    }

    protected static function booted()
    {
        static::created(function ($order) {
            AuditoryController::info(
                TableEnum::ORDERS,
                ActionEnum::INSERT,
                $order->id,
                new_data: $order->toArray()
            );
        });
        static::updating(function ($order) {
            AuditoryController::info(
                TableEnum::ORDERS,
                ActionEnum::UPDATE,
                $order->id,
                old_data: $order->getOriginal(),
                new_data: $order->getDirty()
            );
        });
        static::deleted(function ($order) {
            AuditoryController::info(
                TableEnum::ORDERS,
                ActionEnum::DELETE,
                $order->id,
                old_data: $order->toArray()
            );
        });
    }
}
