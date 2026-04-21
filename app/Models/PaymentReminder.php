<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentReminder extends Model
{
    use HasFactory, \App\Traits\IdentifyTenant;

    protected $fillable = [
        'tenant_id',
        'student_id',
        'channel',
        'stage',
        'amount',
        'due_day',
        'reminder_year',
        'reminder_month',
        'status',
        'channel_response',
        'recipients',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    /**
     * Check if a reminder has already been sent for this student/stage/period.
     */
    public static function alreadySent(int $tenantId, int $studentId, string $stage, int $year, int $month): bool
    {
        return static::where('tenant_id', $tenantId)
            ->where('student_id', $studentId)
            ->where('stage', $stage)
            ->where('reminder_year', $year)
            ->where('reminder_month', $month)
            ->where('status', 'sent')
            ->exists();
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
