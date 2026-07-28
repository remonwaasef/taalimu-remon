# 08_TECH_STACK - Technology Stack Inventory

| Layer | Technology | Version | Purpose / Description |
| :--- | :--- | :--- | :--- |
| **Backend Core** | PHP | `^8.2 \| ^8.4` | Primary runtime environment |
| **Framework** | Laravel | `^12.0` | Core Web Application Framework |
| **Modular Framework** | `nwidart/laravel-modules` | `^12.0` | Modular monolith domain isolation |
| **Frontend Framework** | React | `^19.2.7` | UI library for Inertia dashboard views |
| **Adapter** | Inertia.js (Laravel & React) | `^3.6.1` / `*` | Monolith SPA bridge between Laravel & React |
| **CSS Frameworks** | TailwindCSS & Bootstrap | `^3.4.1` / `^5.3.8` | Styling system and component utilities |
| **Build System** | Vite | `^7.0.7` | Asset bundler and hot module replacement |
| **Database** | MySQL / MariaDB | `8.0+ / 10.6+` | Relational database engine |
| **WebSockets** | Laravel Reverb | `^1.7` | High-performance WebSocket server for live updates |
| **API Auth** | Laravel Sanctum | `^4.2` | SPA & mobile token authentication |
| **Roles & Permissions** | Spatie Laravel-Permission | `^6.23` | Role-based access control (RBAC) |
| **Activity Logging** | Spatie Laravel-Activitylog | `^4.10` | Audit trail and user action tracking |
| **Backup System** | Spatie Laravel-Backup | `^10.2` | Automated database & file backups |
| **Error Monitoring** | Sentry Laravel | `^4.22` | Real-time crash and error reporting |
| **Payment Engines** | Laravel Cashier / PayPal / Paymob | `^16.0` / Custom | Subscription and payment gateway handlers |
| **PDF Generation** | Barryvdh Laravel-DomPDF | `^3.1` | Automated PDF invoice rendering |
| **Testing Engine** | PHPUnit & Playwright JS | `^11.5.3` / `^1.57` | Automated unit, integration, and E2E testing |
