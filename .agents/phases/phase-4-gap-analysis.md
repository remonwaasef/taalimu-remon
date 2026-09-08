# Phase 4 Gap Analysis & ADRs — Intelligence Layer

**Date:** 2026-09-08

---

## What Exists vs What's Required

### ✅ Already Available
- Growth events (profile views, sources, campaigns)
- Demand requests (subject, level, preferences, status)
- Enrollment data (source, campaign, course_id)
- Growth score (6-factor weighted)
- Student risk scoring (rule-based)
- Gemini API integration (partial)
- Chart infrastructure (ApexCharts component)

### ❌ Must Build
- AI Growth Assistant (insight generation from data)
- Recommendations (actionable suggestions)
- Demand forecasting (trend-based prediction)
- Opportunity scoring (demand vs capacity matching)

---

## ADR-13: Intelligence Without External AI API

### Context
Phase 4 requires AI-powered insights. The codebase has partial Gemini integration but no OpenAI. Adding external AI introduces: API costs, latency, hallucination risk, data privacy concerns.

### Decision
Build rule-based/algorithmic intelligence that produces deterministic, auditable insights from verified structured data. No external AI API calls for core intelligence.

### Rationale
- Deterministic: same data → same insight (no hallucination)
- Auditable: every insight shows its supporting data
- Free: no API costs per insight
- Fast: no network latency
- Privacy-safe: data never leaves the server
- Fail-safe: insufficient data → "Not enough data" message

### Consequences
- Insights are rule-based, not generative
- Can be enhanced with AI later (hybrid approach)
- All insights follow the same pattern: fact → evidence → recommendation

---

## ADR-14: Insight Data Structure

### Context
Every insight must identify supporting data, distinguish fact from recommendation, and fail safely.

### Decision
All insights follow a consistent structure:

```php
[
    'type' => 'demand_opportunity',       // insight category
    'severity' => 'high',                 // high, medium, low
    'fact' => '12 students want Mathematics Grade 12',
    'evidence' => ['demand_count' => 12, 'subject' => 'Mathematics'],
    'recommendation' => 'Create a new course to serve this demand',
    'action' => ['label' => 'Create Course', 'route' => 'courses.create'],
    'confidence' => 0.85,                 // 0-1, based on data completeness
]
```

### Rationale
- Structured: easy to render in UI
- Auditable: evidence array shows supporting data
- Safe: confidence score indicates reliability
- Actionable: recommendation + action button

---

## ADR-15: Demand Forecasting via Moving Average

### Context
Need to predict future demand from historical data. No ML infrastructure.

### Decision
Use weighted moving average with trend adjustment:
- Calculate 3-month moving average of demand
- Apply trend factor (increasing/stable/decreasing)
- Project forward 1-3 months
- Show confidence interval based on data quality

### Rationale
- Simple, explainable, auditable
- Works with small datasets (unlike ML)
- No training required
- Can be implemented in SQL

---

## ADR-16: Opportunity Scoring Algorithm

### Context
Need to score opportunities based on demand, capacity, and conversion potential.

### Decision
Score each opportunity 0-100 based on:
- Demand volume (0-30): number of demand requests for this subject/level
- Available capacity (0-25): courses with available seats
- Historical conversion (0-25): past conversion rate for similar demand
- Schedule compatibility (0-10): demand preferred times vs available slots
- Location match (0-10): demand location vs course location

### Rationale
- Transparent: each factor contributes to the score
- Explainable: shows "High demand + available capacity + matching schedule"
- Data-driven: based on actual historical patterns
