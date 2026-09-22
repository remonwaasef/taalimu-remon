<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Sale extends Model
{
    use \App\Traits\BelongsToTenant, \App\Traits\ClearsDashboardCache, HasFactory, LogsActivity, SoftDeletes;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['total_amount', 'paid_amount', 'status', 'payment_method'])
            ->logOnlyDirty();
    }

    protected $fillable = [
        'student_id',
        'subtotal_amount',
        'discount_amount',
        'tax_amount',
        'total_amount',
        'paid_amount',
        'status',
        'payment_method',
        'notes',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }

    public function determineStatus(): string
    {
        if ($this->paid_amount >= $this->total_amount) {
            return 'paid';
        } elseif ($this->paid_amount > 0) {
            return 'partial';
        }

        return 'pending';
    }

    public function updateStatus(): bool
    {
        return $this->update(['status' => $this->determineStatus()]);
    }
}
