<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandRequest extends Model
{
    use \App\Traits\BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'course_id',
        'name',
        'phone',
        'email',
        'subject',
        'level',
        'preferred_days',
        'preferred_time',
        'delivery_mode',
        'location',
        'budget_range',
        'message',
        'status',
        'source',
        'campaign',
        'converted_at',
    ];

    protected $casts = [
        'converted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeForSubject($query, string $subject)
    {
        return $query->where('subject', $subject);
    }

    public function markConverted(): void
    {
        $this->update(['status' => 'converted', 'converted_at' => now()]);
    }

    public function markContacted(): void
    {
        $this->update(['status' => 'contacted']);
    }

    public function markDeclined(): void
    {
        $this->update(['status' => 'declined']);
    }
}
