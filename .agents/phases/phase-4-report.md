# Phase 4 — Intelligence Report

**Date:** 2026-09-08
**Status:** COMPLETE
**Tests:** 38 tests, 96 assertions (all passing)

## What Was Built

### Services
| Service | Location | Description |
|---------|----------|-------------|
| `InsightService` | `app/Services/InsightService.php` | Generates structured insights from demand, capacity, conversion, and growth data |
| `DemandForecastService` | `app/Services/DemandForecastService.php` | Weighted moving average forecast with trend analysis |
| `OpportunityScoringService` | `app/Services/OpportunityScoringService.php` | 5-factor scoring algorithm for growth opportunities |

### Controller
| Controller | Route | Description |
|------------|-------|-------------|
| `GrowthInsightsController` | `GET /growth/insights` | Dashboard for AI-powered insights |

### Views
| View | Path | Description |
|------|------|-------------|
| Insights | `resources/views/growth/dashboard/insights.blade.php` | Full insights dashboard with recommendations, forecast, and opportunities |

## Architecture Decisions

### ADR-13: Rule-Based Intelligence
- All intelligence is algorithmic, deterministic, and auditable
- No external AI API dependency (no OpenAI, Gemini, etc.)
- Free, privacy-safe, instantly available

### ADR-14: Structured Insight Format
Every insight follows:
```json
{
    "type": "demand_opportunity|capacity_optimization|conversion_optimization|growth_quickwin",
    "severity": "high|medium|low",
    "fact": "...",
    "evidence": {...},
    "recommendation": "...",
    "action": {"label": "...", "route": "...", "params": [...]},
    "confidence": 0.0-1.0
}
```

### ADR-15: Demand Forecasting
- Weighted moving average (3:2:1 weights on recent months)
- Linear trend calculation
- Confidence decreases with forecast distance (15% per month)

### ADR-16: Opportunity Scoring
5 weighted factors:
- Demand volume (30%)
- Available capacity (25%)
- Conversion history (25%)
- Schedule compatibility (10%)
- Location match (10%)

## Insight Categories

### Demand Insights
- Detects unmatched demand (students want a subject but no course exists)
- Identifies high-demand subjects without programs
- Severity based on demand count vs conversion rate

### Capacity Insights
- Detects high-demand courses with available seats
- Triggers when capacity > 50% and demand > 2

### Conversion Insights
- Identifies low conversion rates
- Provides specific improvement suggestions

### Growth Quick Wins
- Detects published profiles with no demand collection
- Suggests creating demand collection pages

## Technical Notes

- Used `DB::table()` instead of Eloquent for DemandRequest queries to bypass `TenantScope` global scope in service layer
- `DATE_FORMAT` MySQL function replaced with Carbon month grouping for SQLite test compatibility
- `created_at` must be set via `saveQuietly()` after creation since it's not in `$fillable`
- `center.courses.create` route requires tenant parameter — action buttons in insights view skipped when params missing

## Files Created/Modified

| File | Action |
|------|--------|
| `app/Services/InsightService.php` | Created |
| `app/Services/DemandForecastService.php` | Created |
| `app/Services/OpportunityScoringService.php` | Created |
| `app/Http/Controllers/Growth/GrowthInsightsController.php` | Created |
| `resources/views/growth/dashboard/insights.blade.php` | Created |
| `routes/web.php` | Modified (added insights route) |
| `resources/views/growth/dashboard/index.blade.php` | Modified (added insights link) |
| `tests/Feature/GrowthInsightsTest.php` | Created (7 tests) |
