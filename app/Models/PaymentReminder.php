<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentReminder extends Model
{
    use \App\Traits\BelongsToTenant, HasFactory;

    protected $fillable = [
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

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
