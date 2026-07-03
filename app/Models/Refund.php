<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use \App\Traits\IdentifyTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'sale_id',
        'amount',
        'reason',
        'refund_method',
        'processed_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
