# 06_PROJECT_STRUCTURE - Directory Map & Folder Dictionary

```
taalimu.com/
├── PROJECT_MANIFEST.md       # Root AI manifest entry point
├── README_AI.md              # Root AI operations manual
├── app/                      # Central core infrastructure and shared domain logic
│   ├── Console/              # Artisan CLI commands (scheduled tasks, reminders)
│   ├── Http/                 # Controllers, Middleware, Requests, Resources
│   ├── Models/               # Core Eloquent models (User, Tenant, Student, Course, etc.)
│   ├── Observers/            # Model lifecycle observers
│   ├── Policies/             # Authorization policies (CoursePolicy, StudentPolicy, etc.)
│   ├── Providers/            # Application service providers
│   ├── Repositories/         # Repository implementation classes
│   ├── Scopes/               # Eloquent global query scopes (TenantScope)
│   ├── Services/             # Business logic layer (35+ service classes)
│   └── Traits/               # Shared traits (BelongsToTenant, HandlesFileUploads, etc.)
│
├── Modules/                  # Modular Monolith Domain Modules
│   ├── Admin/                # Central Super Admin management dashboard
│   ├── Api/                  # External REST API endpoints & resources
│   ├── Campus/               # Multi-branch campus management domain
│   ├── Center/               # Primary Tenant Educational Center dashboard & tools
│   ├── Instructor/           # Instructor-specific portal and class tools
│   └── Tenancy/              # Tenant resolution and environment boot logic
│
├── bootstrap/                # Application startup and service provider loading
├── config/                   # Configuration files (app, auth, database, features, services)
├── database/                 # Migrations, Seeders, and Model Factories
├── docs/                     # Numbered AI Knowledge Operating System (00_ to 35_)
├── public/                   # Web root directory (index.php, static assets, uploads)
├── resources/                # Blade views, Inertia React components, SASS, CSS, JS
├── routes/                   # Web, API, Channel, Console routing definitions
├── storage/                  # Storage (logs, uploads, backups, sessions)
└── tests/                    # Automated test suites (PHPUnit & Playwright JS)
```
