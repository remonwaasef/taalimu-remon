<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class NetworkIdentity extends Model
{
    use HasFactory, SoftDeletes, \App\Traits\BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'profilable_type',
        'profilable_id',
        'public_profile_id',
        'public_slug',
        'profile_type',
        'status',
        'published_at',
        'network_visible',
        'discovery_enabled',
        'headline',
        'short_description',
        'city_id',
        'area_id',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'network_visible' => 'boolean',
        'discovery_enabled' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public const PROFILE_TYPE_TEACHER = 'teacher';
    public const PROFILE_TYPE_CENTER = 'center';

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_SUSPENDED = 'suspended';
    public const STATUS_ARCHIVED = 'archived';

    public const VALID_PROFILE_TYPES = [
        self::PROFILE_TYPE_TEACHER,
        self::PROFILE_TYPE_CENTER,
    ];

    public const VALID_STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_PUBLISHED,
        self::STATUS_SUSPENDED,
        self::STATUS_ARCHIVED,
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function profilable(): MorphTo
    {
        return $this->morphTo();
    }

    public function publicProfile(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(PublicProfile::class, 'id', 'public_profile_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function scopeVisible($query)
    {
        return $query->where('network_visible', true);
    }

    public function scopeDiscoverable($query)
    {
        return $query->where('discovery_enabled', true);
    }

    public function scopeTeachers($query)
    {
        return $query->where('profile_type', self::PROFILE_TYPE_TEACHER);
    }

    public function scopeCenters($query)
    {
        return $query->where('profile_type', self::PROFILE_TYPE_CENTER);
    }

    public function scopePubliclyAccessible($query)
    {
        return $query->published()
            ->visible()
            ->whereNotIn('status', [self::STATUS_SUSPENDED, self::STATUS_ARCHIVED]);
    }

    public function scopeDiscoverablePublic($query)
    {
        return $query->publiclyAccessible()
            ->discoverable();
    }

    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    public function isVisible(): bool
    {
        return $this->network_visible && $this->isPublished();
    }

    public function isDiscoverable(): bool
    {
        return $this->discovery_enabled && $this->isVisible();
    }

    public function isSuspended(): bool
    {
        return $this->status === self::STATUS_SUSPENDED;
    }

    public function isArchived(): bool
    {
        return $this->status === self::STATUS_ARCHIVED;
    }

    public function getPublicUrl(): string
    {
        $prefix = $this->profile_type === self::PROFILE_TYPE_TEACHER ? 't' : 'c';
        return url("/{$prefix}/{$this->public_slug}");
    }

    public function getCanonicalUrl(): string
    {
        return $this->getPublicUrl();
    }

    public function getRouteName(): string
    {
        return $this->profile_type === self::PROFILE_TYPE_TEACHER
            ? 'growth.teacher.show'
            : 'growth.center.show';
    }

    public static function getProfilableClass(string $profileType): ?string
    {
        return match ($profileType) {
            self::PROFILE_TYPE_TEACHER => Instructor::class,
            self::PROFILE_TYPE_CENTER => Tenant::class,
            default => null,
        };
    }

    public static function getProfileTypeFromProfilable(Model $profilable): ?string
    {
        if ($profilable instanceof Instructor) {
            return self::PROFILE_TYPE_TEACHER;
        }
        if ($profilable instanceof Tenant) {
            return self::PROFILE_TYPE_CENTER;
        }
        return null;
    }
}