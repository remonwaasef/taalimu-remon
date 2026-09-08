# 15_ROUTES - System Route Architecture

- **`routes/web.php`**: Scoped to main domain (`config('app.tenant_domain')`). Landing page, onboarding (`/register`), unified login (`/login`), social auth, webhooks.
  - **Growth Network Routes (`/growth/*`)**: Authenticated profile and growth tools, protected by `auth` and `\App\Http\Middleware\EnsureGrowthTenant::class` (automatically binds tenant context in container when accessed on central domain).
  - **Public Profile Routes**: Publicly accessible profiles (`/t/{slug}` for instructors, `/c/{slug}` for centers).
- **`Modules/Admin/routes/web.php`**: Prefixed `/admin`, protected by `auth` and `CheckAdminRole`.
- **`Modules/Center/routes/web.php`**: Scoped to tenant subdomains, protected by `auth`, `EnsureOnboardingCompleted`, and `CheckSubscription`.
- **`Modules/Instructor/routes/web.php`**: Prefixed `/instructor`, protected by `auth` and `role:instructor`.
- **`routes/api.php`**: Sanctum API endpoints (`throttle:api`).

