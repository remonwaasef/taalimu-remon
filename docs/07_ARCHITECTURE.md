# 07_ARCHITECTURE - System Architecture & Patterns

```mermaid
graph TD
    Client[Browser / Mobile Client] --> WAF[BasicWAF / Security Middleware]
    WAF --> TenantMiddleware[IdentifyTenant Middleware]
    TenantMiddleware --> Router[Laravel Routing Engine]
    
    subgraph Routing Domains
        Router --> CentralWeb[Central Landing & Register - web.php]
        Router --> AdminMod[Modules/Admin - Super Admin Portal]
        Router --> CenterMod[Modules/Center - Center Management Portal]
        Router --> InstructorMod[Modules/Instructor - Instructor Portal]
        Router --> ApiMod[Modules/Api - REST API]
    end
    
    subgraph Execution Layer
        Controllers[HTTP Controllers / Requests] --> Services[Domain Service Layer]
        Services --> Repositories[Repositories / Models]
        Services --> Notifications[Jobs / Notifications / Messaging]
    end
    
    subgraph Data & Persistence
        Repositories --> DB[(MySQL / MariaDB Database)]
        Services --> Cache[(Redis / File Cache)]
        Notifications --> Gateways[WhatsApp / Telegram / Payment APIs]
    end
```

---

## Architectural Principles

1. **Single-Database Multi-Tenancy Architecture**: All tenant-owned records contain a `tenant_id` foreign key. The `BelongsToTenant` trait automatically injects a global Eloquent scope (`TenantScope`).
2. **Service Layer Separation**: Controllers validate input and delegate execution to domain Service classes (`app/Services/`).
3. **Modular Monolith Pattern**: High-level domain contexts are isolated into standalone modules (`Modules/*`).
