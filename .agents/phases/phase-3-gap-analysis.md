# Phase 3 Gap Analysis — Growth Layer

**Date:** 2026-09-08

---

## What Exists vs What's Required

### ✅ Already Implemented (Phase 1-2)

| Requirement | Status | Location |
|---|---|---|
| Public profiles (teacher/center) | ✅ Done | Phase 1 |
| Program listings | ✅ Done | Phase 2 |
| Waitlist management | ✅ Done | Phase 2 |
| Demand request submission | ✅ Done | Phase 2 |
| Growth event tracking | ✅ Done | Phase 1-2 |
| Onboarding wizard (4-step) | ✅ Exists | OnboardingController + OnboardingService |
| Launchpad (setup checklist) | ✅ Exists | launchpad.blade.php |
| Student risk scoring | ✅ Exists | StudentRiskService |

### ❌ Missing — Must Build

| Requirement | Priority | Gap |
|---|---|---|
| **Acquisition source tracking** | HIGH | No `source` field on enrollment registration flow. Enrollment model has `source`/`campaign` columns (Phase 2) but they're never populated during registration. |
| **Referral links** | HIGH | Zero infrastructure. No referral codes, no invite links, no tracking. |
| **Growth dashboard** | HIGH | No teacher-facing growth dashboard. Only center admin dashboard exists. |
| **Capacity intelligence** | HIGH | No demand vs supply matching. No "X students want this but no course exists" view. |
| **Growth opportunities** | HIGH | No opportunity scoring. No "you could earn $X more by adding Y" view. |
| **Teacher notifications** | HIGH | No in-app notifications for instructors. Only welcome email exists. |
| **Growth score** | MEDIUM | No tenant health/activation score. Only student risk scoring exists. |
| **Onboarding → Growth activation** | MEDIUM | Onboarding wizard is bypassed at registration. No connection between onboarding completion and Growth Network activation. |
| **Empty states** | LOW | No growth-specific empty states ("Invite your first students"). |
| **First value moment** | LOW | No "Your Profile Is Live" celebration after publishing. |

---

## Detailed Gap Analysis

### 1. Acquisition Source Tracking

**What exists:**
- `enrollments.source` and `enrollments.campaign` columns (Phase 2 migration)
- `growth_events.source` and `campaign` columns

**What's missing:**
- Registration flow doesn't capture `?ref=` or `?source=` from URL
- No UTM parameter parsing during registration
- No `source` field in the GroupRegistrationController form
- No attribution reporting for teachers

**What to build:**
- Capture `source`/`campaign` from URL parameters during group registration
- Store attribution on the enrollment record
- Show acquisition source breakdown on growth dashboard

### 2. Referral Links

**What exists:**
- Nothing

**What's missing:**
- No `referral_code` on profiles or tenants
- No referral link generation
- No referral tracking on registration
- No referral rewards/credits

**What to build:**
- Add `referral_code` column to `public_profiles` (unique, auto-generated)
- Generate shareable referral links: `/t/{slug}?ref={code}`
- Track referral attributions in `growth_events`
- Show referral stats on growth dashboard

### 3. Growth Dashboard

**What exists:**
- Center admin dashboard (financial, students, courses)
- Instructor dashboard (courses, students)
- Launchpad (setup checklist)

**What's missing:**
- No dedicated growth analytics view
- No profile view tracking display
- No conversion funnel visualization
- No demand vs supply comparison
- No acquisition source breakdown

**What to build:**
- New growth dashboard page (Blade, authenticated)
- Show: profile views, unique visitors, sources, demand count, waitlist count, enrollment conversions
- Show: growth score, opportunity score
- Show: recommended actions

### 4. Capacity Intelligence

**What exists:**
- `courses.capacity` and `courses.enrolled_count`
- `demand_requests` table with subject/level preferences
- `waitlists` table

**What's missing:**
- No matching engine between demand and existing courses
- No "unmet demand" aggregation
- No "you could create X course to serve Y students" view

**What to build:**
- Demand aggregation service (group by subject/level/preferences)
- Match demand against existing course catalog
- Show unmatched demand as opportunities
- Calculate revenue potential for each opportunity

### 5. Growth Opportunities

**What exists:**
- Nothing

**What's missing:**
- No opportunity scoring
- No demand vs capacity analysis
- No schedule compatibility matching
- No revenue potential calculation

**What to build:**
- OpportunityScoringService: calculate score based on demand volume, capacity, conversion history
- Show "Opportunity Score: 87/100" with explanation
- Show "X students want Mathematics Grade 12 but no course exists"
- Show "You could serve 15 more students with 2 new courses"

### 6. Teacher Notifications

**What exists:**
- Welcome email (WelcomeTeacherMail)
- Admin notifications (AdminNotificationService)
- GeneralNotification class

**What's missing:**
- No in-app notifications for instructors
- No notification preferences
- No notification bell on instructor dashboard
- No triggers for growth events (new demand, waitlist threshold, registration)

**What to build:**
- Extend GeneralNotification to work for instructors
- Add notification triggers for growth events
- Add notification bell to instructor dashboard
- Add notification preferences page

### 7. Growth Score

**What exists:**
- Student risk scoring (StudentRiskService)

**What's missing:**
- No tenant-level growth score
- No profile completeness calculation
- No activation milestones

**What to build:**
- GrowthScoreService: calculate 0-100 score based on:
  - Profile completeness (has photo, bio, headline)
  - Active programs count
  - Conversion rate (visitors → enrollments)
  - Response rate (demand requests answered)
  - Retention (repeat students)
  - Demand fulfillment
- Show score on growth dashboard
- Show breakdown of how score is calculated

### 8. Onboarding → Growth Activation

**What exists:**
- 4-step onboarding wizard (language, instructors, courses, students)
- Launchpad (setup checklist)
- EnsureOnboardingCompleted middleware (disabled)

**What's missing:**
- No "Activate Growth Network" step
- No connection between onboarding completion and public profile creation
- No引导 to publish first program after onboarding

**What to build:**
- Add Step 5 to onboarding: "Activate Your Teaching Presence"
- After onboarding,引导 to profile settings
- Auto-create draft public profile when onboarding completes
- Show "Your profile is X% ready" progress indicator

---

## Implementation Priority

### P0 — Must Have (Week 1)
1. Acquisition source tracking on registration
2. Growth dashboard (basic: views, demand, waitlist)
3. Teacher notifications (new demand, registration)

### P1 — Should Have (Week 2)
4. Referral links
5. Capacity intelligence (demand vs supply)
6. Growth score

### P2 — Nice to Have (Week 3)
7. Opportunity scoring
8. Onboarding → Growth activation flow
9. Empty states and first value moment

---

## Database Changes Required

### New Tables
- `referral_links` — referral_code, profile_id, click_count, conversions
- `teacher_notifications` — user_id, type, title, message, read_at, data
- `growth_scores` — tenant_id, score, breakdown JSON, calculated_at

### New Columns
- `public_profiles.referral_code` — unique, auto-generated
- `public_profiles.photo_url` — profile photo for completeness scoring
- `tenants.growth_activated` — boolean, whether Growth Network is active

### Existing Columns to Populate
- `enrollments.source` — populate from URL params during registration
- `enrollments.campaign` — populate from URL params during registration
