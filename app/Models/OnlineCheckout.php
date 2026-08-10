<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Server-side map between a Paymob order id and the checkout context
 * (tenant, package, cycle, amount). Because the Paymob order id is the
 * only identifier covered by the webhook HMAC, this map is the trusted
 * source of truth for subscription activation (PAY-2).
 */
class OnlineCheckout extends Model
{
    protected $fillable = [
        'paymob_order_id',
        'tenant_id',
        'package_slug',
        'billing_cycle',
        'is_change',
        'amount_cents',
        'merchant_order_id',
        'status',
    ];

    protected $casts = [
        'is_change' => 'boolean',
        'amount_cents' => 'integer',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}