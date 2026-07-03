<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointLog extends Model
{
    use \App\Traits\BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'points',
        'reason',
        'referenceable_type',
        'referenceable_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function referenceable()
    {
        return $this->morphTo();
    }
}
