<?php

namespace App\Models;

use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Classroom extends Model
{
    use HasFactory, LogsActivity, \App\Traits\IdentifyTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'capacity',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'capacity'])
            ->logOnlyDirty();
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
