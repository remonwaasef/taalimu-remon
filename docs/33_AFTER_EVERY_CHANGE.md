# 33_AFTER_EVERY_CHANGE - Post-Task Documentation Matrix

> **Purpose**: Defines mandatory documentation updates required immediately after completing any code change, feature addition, bug fix, or database modification.

---

# Mandatory Action Matrix

Whenever a code modification is completed, the developer or AI assistant MUST update the affected documentation files according to this matrix:

| If you modified... | You MUST update... |
| :--- | :--- |
| **Database Schema / Migrations** | Update [11_DATABASE.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/11_DATABASE.md) and [12_MODELS.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/12_MODELS.md) |
| **Routes or Domain Endpoints** | Update [15_ROUTES.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/15_ROUTES.md) |
| **RESTful APIs or Webhook Payloads** | Update [16_API.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/16_API.md) |
| **Business Rules or Domain Workflows** | Update [17_BUSINESS_LOGIC.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/17_BUSINESS_LOGIC.md) |
| **User Interface / Design Tokens / Views**| Update [23_UI_GUIDE.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/23_UI_GUIDE.md) and [21_FRONTEND.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/21_FRONTEND.md) |
| **Authentication Flow / OAuth / 2FA / OTP**| Update [18_AUTHENTICATION.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/18_AUTHENTICATION.md) |
| **Permissions, Roles, or Policies** | Update [19_AUTHORIZATION.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/19_AUTHORIZATION.md) and [20_PERMISSIONS.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/20_PERMISSIONS.md) |
| **Composer / NPM Dependencies** | Update [25_DEPENDENCIES.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/25_DEPENDENCIES.md) |
| **Architecture or Module Design** | Update [07_ARCHITECTURE.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/07_ARCHITECTURE.md) and [10_MODULES.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/10_MODULES.md) |
| **Features or Sub-systems** | Update [09_FEATURES.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/09_FEATURES.md) |
| **Services or Domain Handlers** | Update [14_SERVICES.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/14_SERVICES.md) |

---

# Mandatory Universal Updates (ALWAYS REQUIRED)

After **EVERY** completed task, you MUST update:

1. **[34_CHANGELOG.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/34_CHANGELOG.md)**: Add a concise entry under `[Unreleased]` detailing the changes made.
2. **[01_MASTER_CONTEXT.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/01_MASTER_CONTEXT.md)**: Update any affected overview summaries or core references.
3. **[02_CURRENT_STATE.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/02_CURRENT_STATE.md)**: Update recent major work, task statuses, or milestone checkboxes.
