<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Guardian extends Model
{
    use HasFactory, LogsActivity, \App\Traits\IdentifyTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'phone',
        'job',
        'email',
        'address',
        'status',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'phone', 'status'])
            ->logOnlyDirty();
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
