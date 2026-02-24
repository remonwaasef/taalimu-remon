<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    use HasFactory, \App\Traits\IdentifyTenant;

    protected $fillable = [
        'tenant_id',
        'instructor_id',
        'amount',
        'payment_method',
        'transaction_reference',
        'payout_date',
        'processed_by',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payout_date' => 'date',
    ];

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
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
