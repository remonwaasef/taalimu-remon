<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineClassMessage extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'online_class_id',
        'user_id',
        'message',
        'type',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function onlineClass()
    {
        return $this->belongsTo(OnlineClass::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
