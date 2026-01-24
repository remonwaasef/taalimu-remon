<?php

namespace Modules\Center\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Tenant;

class Branch extends Model
{
    use HasFactory, SoftDeletes, \App\Traits\IdentifyTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'address',
        'phone',
        'manager_id', // Optional: link to a user who is the manager
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
