# Phase 3 Architecture Decision Records

**Date:** 2026-09-08

---

## ADR-7: Acquisition Source Tracking Strategy

### Context
Enrollment model already has `source` and `campaign` columns (Phase 2), but they're never populated during the registration flow. We need to capture attribution data when students register.

### Decision
Capture `source` and `campaign` from URL query parameters (`?ref=`, `?source=`, `?campaign=`) during the group registration flow. Store them on the `enrollments` record.

### Rationale
- URL parameters are the simplest attribution mechanism
- No external tracking service needed
- Works with QR codes, shared links, social media
- Compatible with future UTM tracking

### Consequences
- Registration controller must pass source/campaign through the session
- After successful enrollment, update the enrollment record with attribution
- Growth events will also carry source/campaign for funnel tracking

---

## ADR-8: Referral Link Architecture

### Context
We need referral tracking to attribute new students to referrers. No existing infrastructure.

### Decision
- Add `referral_code` column to `public_profiles` (8-char random alphanumeric, unique)
- Generate referral links: `/t/{slug}?ref={code}`
- Track referral clicks as `growth_events` with event_name `referral_clicked`
- Track referral conversions as `growth_events` with event_name `referral_converted`
- Store referral attribution on the enrollment record

### Rationale
- `public_profiles` is the right home because it's the public-facing entity
- Lightweight: no separate referral_links table needed for MVP
- Growth events already support arbitrary event tracking
- Referral code is short enough to share verbally

### Consequences
- Profile settings page needs a "Copy Referral Link" button
- Growth dashboard shows referral click/conversion stats
- Referral code must be regenerated if compromised (admin action)

---

## ADR-9: Growth Dashboard as Blade (Not Inertia)

### Context
The existing growth profile pages are Blade. The center admin dashboard is Blade. Only 2 pages use Inertia (student list, student detail).

### Decision
Build the growth dashboard as a Blade view, not Inertia/React.

### Rationale
- Consistent with existing growth network pages (all Blade)
- No new JS build dependencies
- Faster to implement
- Inertia is only used for the student management module
- Blade + Alpine.js is sufficient for the dashboard's interactivity

### Consequences
- Dashboard will use Alpine.js for any interactive charts/filters
- Data will be passed from controller to view via compact()
- Charts can use Chart.js or similar lightweight library via CDN

---

## ADR-10: Notification System — Extend GeneralNotification

### Context
A `GeneralNotification` class already exists and is used for admin notifications. Instructors currently receive no in-app notifications.

### Decision
Extend the existing `GeneralNotification` system to work for instructors. Add new notification types for growth events.

### Rationale
- Reuse existing infrastructure rather than building from scratch
- `GeneralNotification` already handles in-app storage, marking as read
- Database structure is already tenant-aware
- Just need new event triggers and a notification bell on the instructor dashboard

### Consequences
- Add `notifiable_id`/`notifiable_type` polymorphic support if not already present
- Create `GrowthNotificationService` to dispatch notifications for growth events
- Add notification bell to instructor navigation
- Add notification preferences page (future)

---

## ADR-11: Growth Score Calculation

### Context
The master prompt requires a transparent, explainable growth score. Student risk scoring exists but no tenant-level scoring.

### Decision
Create a `GrowthScoreService` that calculates a 0-100 score based on weighted factors:

| Factor | Weight | Source |
|---|---|---|
| Profile completeness | 20% | public_profiles fields |
| Active programs | 20% | courses.count where published |
| Conversion rate | 20% | enrollments / profile_views |
| Response rate | 15% | demand requests answered / total |
| Retention | 15% | returning students / total students |
| Demand fulfillment | 10% | enrollments from demand / total demand |

### Rationale
- Weighted factors are transparent and explainable
- Each factor maps to a real business outcome
- No black-box scoring
- Score can be shown as "Your Growth Score: 72/100" with breakdown

### Consequences
- Score is calculated on-demand (not cached) for accuracy
- Dashboard shows score + breakdown + "How to improve" tips
- Score changes are tracked over time via `growth_scores` table
- First-time users start with score 0, score increases as they complete steps

---

## ADR-12: Onboarding Integration with Growth Network

### Context
The existing onboarding wizard is bypassed at registration. We need to引导 new tenants through Growth Network activation.

### Decision
- Keep the existing 4-step onboarding wizard as-is
- After onboarding completion, redirect to profile settings with a "Complete Your Teaching Presence" banner
- Add a `growth_activated` boolean column to `tenants` table
- When `growth_activated` is true, show growth features in the dashboard
- The onboarding wizard does NOT need to be modified — it handles center setup, not growth activation

### Rationale
- Separation of concerns: onboarding = center setup, growth = acquisition
- Don't break existing onboarding flow
- Growth activation is a separate milestone
- Teacher can activate Growth Network at any time, not just during onboarding

### Consequences
- New activation flow: Profile Settings → "Activate Growth Network" button
- When activated, auto-create draft public profile
- Dashboard shows "Activate Growth Network" CTA if not activated
- Growth features are hidden until activated (feature gate)
