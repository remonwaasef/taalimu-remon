<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionLog extends Model
{
    use \App\Traits\BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'operation_type',
        'package_slug',
        'package_name',
        'billing_cycle',
        'gateway',
        'amount',
        'transaction_id',
        'starts_at',
        'ends_at',
        'status',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Log a subscription operation.
     */
    public static function logOperation(
        int $tenantId,
        string $operationType,
        ?string $packageSlug,
        ?string $packageName,
        ?string $billingCycle,
        ?string $gateway,
        float $amount,
        ?string $transactionId,
        $startsAt,
        $endsAt,
        string $status = 'active'
    ): self {
        return self::create([
            'tenant_id' => $tenantId,
            'operation_type' => $operationType,
            'package_slug' => $packageSlug,
            'package_name' => $packageName,
            'billing_cycle' => $billingCycle,
            'gateway' => $gateway,
            'amount' => $amount,
            'transaction_id' => $transactionId,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'status' => $status,
        ]);
    }
}
