# 30_TESTING_GUIDE - Automated & Manual Testing Strategy

> **Purpose**: Guides testing procedures across PHPUnit automated suite, Playwright JS E2E browser tests, and manual verification steps.

---

## Testing Ecosystem

```
tests/
├── Feature/        # Laravel Feature Tests (HTTP endpoints, database state, auth)
├── Unit/           # Pure PHP Unit Tests (Services, DTOs, Helpers)
└── e2e/            # Playwright JS End-to-End browser test scripts
```

---

## Running Test Suites

### 1. Automated PHPUnit Suite
```bash
# Run all tests
php artisan test

# Run tests in parallel
php artisan test --parallel

# Run specific feature test
php artisan test --filter=StudentTest
```

### 2. End-to-End Playwright JS Suite (`playwright.config.js`)
```bash
# Install browsers
npx playwright install

# Run E2E browser tests
npx playwright test
```

---

## Testing Domain Workflows

### 1. Multi-Tenant CRUD Testing
- Verify that creating a student or course in Tenant A does NOT appear in Tenant B query results (`tenant_id` scope isolation).
- Test soft deletes (`SoftDeletes` trait) and restore behavior.

### 2. Authentication & Authorization Testing
- Test guest access denial to `/dashboard` and `/admin`.
- Verify `CheckAdminRole` middleware redirects non-admin users attempting to access `/admin`.
- Test OTP verification rate limiters (`throttle:10,5`).

### 3. Payment Gateway Webhook Testing
- Test mock payment gateway (`MockGateway`) checkout success URL.
- Test PayPal webhook signature verification headers with sample JSON payload.
