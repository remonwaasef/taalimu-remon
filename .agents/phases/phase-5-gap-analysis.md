# Phase 5 Gap Analysis & ADRs — Network Layer

**Date:** 2026-09-08

---

## What Exists vs What's Required

### ✅ Already Available
- Public profiles (teacher + center) — Phase 1
- Program listings with courses — Phase 2
- Demand request system — Phase 2
- Waitlist system — Phase 2
- Referral codes on profiles — Phase 3
- Growth score calculation — Phase 3
- Growth events tracking — Phase 1

### ❌ Must Build
- Teacher discovery page (public search)
- Center discovery page (public search)
- Review/rating system (verified reputation)
- Referral tracking with rewards
- Marketplace listings (student needs → teacher responses)

---

## ADR-17: Public Discovery Without Authentication

### Context
Discovery pages (teacher search, center search) must be accessible to unauthenticated visitors (parents, students) to maximize SEO and conversion. However, we must prevent abuse (scraping, enumeration).

### Decision
- Discovery routes are public (no `auth` middleware)
- Rate limited: `throttle:60,1` (60 requests per minute)
- No sensitive data exposed (no emails, phones, internal IDs)
- Pagination enforced (max 20 per page)
- Full-text search via `LIKE` (no Elasticsearch needed at this scale)

### Rationale
- Public discovery is the core growth loop — must be frictionless
- Rate limiting prevents abuse
- Pagination prevents memory exhaustion
- Simple search works for MVP; can upgrade to full-text search later

### Consequences
- SEO-friendly: public pages crawlable by Google
- No authentication barrier for discovery
- Privacy: only published profile data shown

---

## ADR-18: Review System with Tenant Verification

### Context
Reviews/ratings build trust. Need to prevent fake reviews while keeping the system simple.

### Decision
- Reviews tied to `tenant_id` (center or teacher)
- One review per student per teacher/center (unique constraint)
- Rating: 1-5 integer
- Optional: linked to `enrollment_id` (verified review) or null (unverified)
- Public display: reviews visible on public profiles
- Tenant can respond once to each review

### Data Structure
```php
Schema::create('reviews', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
    $table->foreignId('reviewer_id')->constrained('users')->cascadeOnDelete();
    $table->nullableForeignId('enrollment_id')->constrained('enrollments')->nullOnDelete();
    $table->string('reviewable_type'); // Instructor or Center
    $table->unsignedBigInteger('reviewable_id');
    $table->unsignedTinyInteger('rating'); // 1-5
    $table->text('comment')->nullable();
    $table->text('response')->nullable(); // tenant response
    $table->boolean('verified')->default(false); // true if linked to enrollment
    $table->boolean('approved')->default(true);
    $table->timestamps();

    $table->unique(['tenant_id', 'reviewer_id', 'reviewable_type', 'reviewable_id']);
    $table->index(['reviewable_type', 'reviewable_id']);
});
```

### Rationale
- `reviewable_type` + `reviewable_id` polymorphic — supports both teachers and centers
- `enrollment_id` link proves the reviewer actually took a course
- Unique constraint prevents duplicate reviews
- Tenant response builds trust

---

## ADR-19: Referral System with Simple Rewards

### Context
Student referrals drive growth. Need tracking without complex reward logic.

### Decision
- Each published profile has a unique referral code (already exists from Phase 3)
- When a referred student enrolls, the referrer gets credited
- Track referral chain: referrer → referred student → enrollment
- Reward: tracked in `growth_events` (type: `referral_reward`)
- No complex point system — just credit tracking

### Data Structure
```php
Schema::create('referrals', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
    $table->foreignId('referrer_id')->constrained('users')->cascadeOnDelete();
    $table->foreignId('referred_id')->constrained('users')->cascadeOnDelete();
    $table->foreignId('enrollment_id')->nullable()->constrained('enrollments')->nullOnDelete();
    $table->string('code_used'); // the referral code that was used
    $table->enum('status', ['pending', 'completed', 'expired'])->default('pending');
    $table->timestamp('rewarded_at')->nullable();
    $table->timestamps();

    $table->unique(['tenant_id', 'referrer_id', 'referred_id']);
    $table->index(['tenant_id', 'code_used']);
});
```

### Rationale
- Simple tracking: who referred whom, did they enroll
- `code_used` stores the actual code for audit
- Status tracking: pending → completed (enrolled) → rewarded
- No complex reward calculation — just tracking

---

## ADR-20: Marketplace Listings (Student Needs)

### Context
Students/parents post what they need; teachers respond. Simpler than a full marketplace.

### Decision
- Students post listings: "I need Math tutor for Grade 12"
- Teachers browse and respond (via existing demand system or direct contact)
- Listings expire after 30 days
- One active listing per student per subject

### Data Structure
```php
Schema::create('marketplace_listings', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('subject');
    $table->string('level')->nullable();
    $table->text('description')->nullable();
    $table->string('location')->nullable();
    $table->string('budget_range')->nullable();
    $table->string('preferred_schedule')->nullable();
    $table->enum('status', ['active', 'matched', 'expired', 'closed'])->default('active');
    $table->unsignedInteger('view_count')->default(0);
    $table->timestamp('expires_at');
    $table->timestamps();

    $table->index(['tenant_id', 'status', 'subject']);
    $table->index(['status', 'expires_at']); // for expiry job
});
```

### Rationale
- Extends existing demand system with a public-facing marketplace
- Expiry prevents stale listings
- View count for popularity metrics
- Status tracking: active → matched/expired/closed

---

## ADR-21: Discovery Search Algorithm

### Context
Need to rank teachers/centers in search results without ML.

### Decision
Simple scoring algorithm:
1. **Relevance** (subject match): +40 points
2. **Rating** (average review): +25 points
3. **Response time** (recent activity): +15 points
4. **Availability** (has open courses): +10 points
5. **Profile completeness**: +10 points

Sort by score descending. Ties broken by most recent activity.

### Rationale
- Simple, explainable, auditable
- Works without ML infrastructure
- Can be tuned by adjusting weights
- Returns relevant results for "Math tutor near me" queries

---

## Implementation Plan

### Migrations
1. `reviews` table
2. `referrals` table
3. `marketplace_listings` table

### Models
1. `Review` (with BelongsToTenant)
2. `Referral` (with BelongsToTenant)
3. `MarketplaceListing` (with BelongsToTenant)

### Services
1. `ReviewService` — create, respond, get average rating
2. `ReferralService` — track referrals, credit rewards
3. `DiscoveryService` — search, rank, paginate
4. `MarketplaceService` — CRUD listings, expiry

### Controllers
1. `DiscoveryController` — teacher/center search pages
2. `ReviewController` — submit reviews, view on profiles
3. `ReferralController` — referral dashboard
4. `MarketplaceController` — browse/post listings

### Routes
| Route | Method | Description |
|-------|--------|-------------|
| `/discover/teachers` | GET | Teacher discovery |
| `/discover/centers` | GET | Center discovery |
| `/discover/listings` | GET | Marketplace browse |
| `/p/{slug}/reviews` | GET | Reviews on profile |
| `/p/{slug}/reviews` | POST | Submit review |
| `/growth/referrals` | GET | Referral dashboard |
| `/growth/marketplace/create` | GET | Create listing |
| `/growth/marketplace` | POST | Store listing |

### Views
1. `growth/discovery/teachers.blade.php`
2. `growth/discovery/centers.blade.php`
3. `growth/discovery/listings.blade.php`
4. `growth/public/reviews.blade.php`
5. `growth/dashboard/referrals.blade.php`
6. `growth/dashboard/marketplace-create.blade.php`
