<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'table_name',
        'record_id',
        'action',
        'changes',
        'user_id',
        'ip_address',
        'user_agent',
    ];
}
