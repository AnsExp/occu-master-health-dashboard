<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Metadata extends Model
{
    protected $fillable = [
        'meta_type',
        'meta_id',
        'meta_key',
        'meta_value'
    ];
}
