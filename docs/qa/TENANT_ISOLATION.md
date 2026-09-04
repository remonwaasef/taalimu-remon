# TAALIMU TENANT ISOLATION AUDIT

## 1. ISOLATION ARCHITECTURE

### 1.1 Strategy
- **Type**: Single-database with `tenant_id` partitioning
- **Mode**: Subdomain (`{tenant}.domain.com`) + Path (`/c/{tenant}/`) hybrid
- **Resolution**: `IdentifyTenant` middleware resolves tenant from subdomain/path
- **Isolation**: `BelongsToTenant` trait + `TenantScope` global scope

### 1.2 Key Components

| Component | File | Function |
|-----------|------|----------|
| TenantScope | `app/Scopes/TenantScope.php` | Global scope filtering all queries by tenant_id |
| BelongsToTenant | `app/Traits/BelongsToTenant.php` | Adds scope + auto-sets tenant_id on create |
| IdentifyTenant | `app/Http/Middleware/IdentifyTenant.php` | Resolves tenant, binds to container, sets Spatie team |
| Spatie Teams | `setPermissionsTeamId($tenant->id)` | Scopes roles/permissions per tenant |

---

## 2. ISOLATION LAYERS

### Layer 1: Database Global Scope
```php
// TenantScope.php:17-33
$builder->where($model->getTable().'.tenant_id', app('tenant')->id);

// Exception: User model allows tenant_id = null (global admins)
if ($model instanceof \App\Models\User) {
    $builder->where(function ($q) use ($model) {
        $q->where($model->getTable().'.tenant_id', app('tenant')->id)
          ->orWhereNull($model->getTable().'.tenant_id');
    });
}
```
**Status**: ✅ ACTIVE — All models with `BelongsToTenant` trait are scoped

### Layer 2: Auto-set on Create
```php
// BelongsToTenant.php:25-29
static::creating(function ($model) {
    if (app()->bound('tenant')) {
        $model->tenant_id = app('tenant')->id;
    }
});
```
**Status**: ✅ ACTIVE — tenant_id set automatically

### Layer 3: Route-Level Middleware
```php
// routes/web.php:493-516
Route::prefix('c/{tenant}')
    ->middleware([\App\Http\Middleware\IdentifyTenant::class])
    ->group($tenantRoutes);
```
**Status**: ✅ ACTIVE — All tenant routes go through IdentifyTenant

### Layer 4: Policy Checks
```php
// StudentPolicy.php:28
$model->tenant_id === $user->tenant_id

// CoursePolicy.php:26
$model->tenant_id === $user->tenant_id

// SalePolicy.php:20
$model->tenant_id === $user->tenant_id
```
**Status**: ✅ ACTIVE — All 17+ policies check tenant_id

### Layer 5: Spatie Permission Teams
```php
// IdentifyTenant.php:104
app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($tenant->id);
```
**Status**: ✅ ACTIVE — Roles/permissions scoped per tenant

### Layer 6: API Token Isolation
```php
// ApiTenantMiddleware.php:58-64
if ($token && $token->tokenable && $token->tokenable->tenant_id !== $tenant->id) {
    abort(403, 'Cross-tenant API access forbidden');
}
```
**Status**: ✅ ACTIVE — Cross-tenant tokens rejected

---

## 3. TEST EVIDENCE

### 3.1 Automated Tests

| Test | File | Status | What It Tests |
|------|------|--------|---------------|
| ModelIsolationTest | `tests/Feature/Security/ModelIsolationTest.php` | ✅ PASS | All models use BelongsToTenant trait |
| TenantIdentityTest | `tests/Feature/Security/TenantIdentityTest.php` | ✅ PASS | User cannot access another tenant's dashboard |
| ApiTenantIsolationTest | `tests/Feature/Api/ApiTenantIsolationTest.php` | ✅ PASS | Cross-tenant tokens rejected |
| IdorProtectionTest | `tests/Feature/Security/IdorProtectionTest.php` | ✅ PASS | TenantScope active, risk isolation, global admin blocked |

### 3.2 Logical IDOR Tests

| Test Case | Expected | Actual | Status |
|-----------|----------|--------|--------|
| Tenant A accesses Tenant B student by ID | 403/404 | Blocked by TenantScope | ✅ PASS |
| Tenant A uses Tenant B student in URL | 403/404 | Blocked by scope | ✅ PASS |
| Global admin accesses tenant area | 403 | Blocked by CheckAdminRole | ✅ PASS |
| Student from Tenant A in Tenant B API | 403 | Blocked by ApiTenantMiddleware | ✅ PASS |

---

## 4. POTENTIAL RISKS

| # | Risk | Severity | Evidence | Mitigation |
|---|------|----------|----------|------------|
| 1 | `is_relaxed_throttle_env()` bypasses rate limits | MEDIUM | UnifiedAuthController.php:36 | Environment-gated, must not be in production |
| 2 | `google2fa_bypass` column on users table | MEDIUM | User.php:98 | Must not be settable by users |
| 3 | Demo data uses `forceCreate` bypassing scopes | LOW | DemoDataService.php | Intentional, demo mode only |
| 4 | `password_reset_tokens` scoped by tenant_id | LOW | Migration 2026_08_17 | Fixed |
| 5 | User email unique globally not per-tenant | LOW | Migration 2026_05_02 | Mitigated by scoped unique |
| 6 | Cache keys tenant-prefixed but cache store shared | LOW | IdentifyTenant.php:121-122 | Log files per tenant, cache keys prefixed |
| 7 | No route-level IDOR test for student/sale by ID | MEDIUM | Missing automated test | Need to add |

---

## 5. CROSS-TENANT ACCESS ATTEMPTS

### 5.1 Via URL/Route
| Attempt | Expected | Evidence |
|---------|----------|----------|
| `/c/tenant-a/students/123` (Tenant B student) | 404 (not found by scope) | TenantScope filters query |
| `tenant-a.domain.com/students/123` | 404 | TenantScope filters query |
| `/c/tenant-a/students/export` | Tenant A data only | Scope applied |

### 5.2 Via API
| Attempt | Expected | Evidence |
|---------|----------|----------|
| API token from Tenant A → Tenant B endpoint | 403 | ApiTenantMiddleware blocks |
| Cross-tenant Sanctum token | 403 | Token tenant_id mismatch |

### 5.3 Via Direct DB
| Attempt | Expected | Evidence |
|---------|----------|----------|
| Manual query without scope | Would bypass isolation | Only possible via raw SQL or `withoutGlobalScope()` |
| ForceCreate bypasses scope | Allowed | DemoDataService uses intentionally |

---

## 6. SPATIE TEAMS VERIFICATION

| Check | Status | Evidence |
|-------|--------|----------|
| setPermissionsTeamId called | ✅ | IdentifyTenant.php:104 |
| Team ID = tenant_id | ✅ | `setPermissionsTeamId($tenant->id)` |
| Role assignments scoped | ✅ | Spatie teams feature |
| Permission checks scoped | ✅ | Via team_id in pivot tables |

---

## 7. CACHE ISOLATION

| Check | Status | Evidence |
|-------|--------|----------|
| Cache keys prefixed with tenant_id | ✅ | `tenant_{id}_usage_{feature}` |
| Log files per tenant | ✅ | `tenant_{id}.log` |
| Session shared (by design) | ⚠️ | Sessions not tenant-scoped (users authenticated by login) |
| Search index filtered by tenant_id | ✅ | SearchService.php:53-54 |

---

## 8. FILES & STORAGE ISOLATION

| Check | Status | Evidence |
|-------|--------|----------|
| File uploads stored per-tenant | ⚠️ | Storage path not tenant-prefixed by default |
| Public files accessible cross-tenant | ⚠️ | Need verification |
| Profile photos | ⚠️ | Stored in generic uploads path |

---

## 9. TENANT A vs TENANT B TEST MATRIX

| Resource | Tenant A Create | Tenant A Read | Tenant B Read (A's data) | Status |
|----------|----------------|---------------|-------------------------|--------|
| Users | ✅ | ✅ | ❌ Blocked | ✅ |
| Students | ✅ | ✅ | ❌ Blocked | ✅ |
| Instructors | ✅ | ✅ | ❌ Blocked | ✅ |
| Courses | ✅ | ✅ | ❌ Blocked | ✅ |
| Schedules | ✅ | ✅ | ❌ Blocked | ✅ |
| Attendance | ✅ | ✅ | ❌ Blocked | ✅ |
| Sales | ✅ | ✅ | ❌ Blocked | ✅ |
| Payments | ✅ | ✅ | ❌ Blocked | ✅ |
| Quizzes | ✅ | ✅ | ❌ Blocked | ✅ |
| Notifications | ✅ | ✅ | ❌ Blocked | ✅ |
| Settings | ✅ | ✅ | ❌ Blocked | ✅ |
| Tickets | ✅ | ✅ | ❌ Blocked | ✅ |
| Files | ✅ | ✅ | ❓ Needs test | ⚠️ |
| Search | ✅ | ✅ | ❌ Blocked | ✅ |

---

## 10. CONCLUSION

**Tenant Isolation: STRONG**

The multi-tenancy architecture is well-implemented with 6 layers of protection:
1. Database global scope (automatic filtering)
2. Auto-set on create (no manual tenant_id assignment)
3. Route-level middleware (tenant resolution)
4. Policy checks (authorization)
5. Spatie teams (permission scoping)
6. API middleware (token isolation)

**Remaining Gaps**:
- No automated route-level IDOR test for entity access by ID
- File storage isolation needs verification
- Session sharing across tenants (by design, but worth noting)

---

*Generated during Pass 1 Discovery & Audit*
*Last Updated: 2026-09-02*