<?php

namespace App\Models\Modules\Center\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use \App\Traits\BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'classroom_id',
        'name',
        'code',
        'type',
        'status',
        'purchase_date',
        'cost',
        'notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'cost' => 'decimal:2',
    ];

    public function classroom()
    {
        return $this->belongsTo(\App\Models\Classroom::class);
    }

    public function tenant()
    {
        return $this->belongsTo(\App\Models\Tenant::class);
    }
}
