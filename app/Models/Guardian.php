<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Guardian extends Model
{
    use \App\Traits\IdentifyTenant, HasFactory, LogsActivity;

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
