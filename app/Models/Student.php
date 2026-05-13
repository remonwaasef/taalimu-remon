<?php

namespace App\Models;

use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Student extends Model
{
    use HasFactory, LogsActivity, SoftDeletes, \App\Traits\IdentifyTenant, \App\Traits\ClearsDashboardCache;

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($model) {
            $model->clearCache();
        });

        static::deleted(function ($model) {
            $model->clearCache();
        });
    }

    public function clearCache()
    {
        cache()->forget('student_profile_' . $this->id);
    }

    public function getCachedProfile()
    {
        return cache()->remember('student_profile_' . $this->id, now()->addHours(6), function () {
            return $this->load(['user', 'grade']);
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'status', 'guardian_id'])
            ->logOnlyDirty();
    }



    protected $fillable = [
        'tenant_id',
        'user_id',
        'grade_id',
        'guardian_id',
        'code',
        'national_id',
        'name',
        'email',
        'phone',
        'birth_date',
        'gender',
        'profile_photo',
        'address',
        'parent_name',
        'parent_phone',
        'parent_email',
        'parent_job',
        'parent_relation',
        'emergency_phone',
        'school_name',
        'section_type',
        'joined_at',
        'grade_level',
        'status',
        'monthly_fee',
        'payment_due_day',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'joined_at' => 'date',
        'monthly_fee' => 'decimal:2',
        'payment_due_day' => 'integer',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'tenant_id',
        'address',
        'user_id',
        // Legacy parent fields hidden to encourage using the guardian relationship
        'parent_phone',
        'parent_job',
        'parent_relation',
        'emergency_phone',
        'parent_name',
    ];

    /**
     * The "booted" method of the model.
     */
    // booted method removed in favor of IdentifyTenant trait

    /**
     * Get the grade level name in Arabic
     */
    public function getGradeLevelNameAttribute()
    {
        if ($this->grade) {
            return ($this->grade->stage?->name ? $this->grade->stage->name . ' - ' : '') . $this->grade->name;
        }

        $grades = [
            '1' => 'الأول الابتدائي',
            '2' => 'الثاني الابتدائي',
            '3' => 'الثالث الابتدائي',
            '4' => 'الرابع الابتدائي',
            '5' => 'الخامس الابتدائي',
            '6' => 'السادس الابتدائي',
            '7' => 'الأول الإعدادي',
            '8' => 'الثاني الإعدادي',
            '9' => 'الثالث الإعدادي',
            '10' => 'الأول الثانوي',
            '11' => 'الثاني الثانوي',
            '12' => 'الثالث الثانوي',
        ];

        return $grades[$this->grade_level] ?? $this->grade_level;
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function bookings()
    {
        return $this->hasMany(\App\Models\Booking::class);
    }

    public function guardian()
    {
        return $this->belongsTo(Guardian::class);
    }

    public function enrollments()
    {
        return $this->hasMany(\App\Models\Enrollment::class, 'user_id', 'user_id');
    }
    
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function certificates()
    {
        return $this->hasMany(\App\Models\Certificate::class);
    }
}
