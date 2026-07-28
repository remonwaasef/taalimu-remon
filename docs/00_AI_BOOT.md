# 00_AI_BOOT - Session Boot Sequence & Task Classifier

> **READ FIRST**: This is the first document read by an AI assistant at the start of any conversation.

---

## Session Boot Sequence

1. Read [docs/01_MASTER_CONTEXT.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/01_MASTER_CONTEXT.md) to understand overall system architecture, models, and domain boundaries.
2. Read [docs/03_AI_RULES.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/03_AI_RULES.md) for mandatory operating rules and coding constraints.
3. Classify the user task using the **Task Classification Matrix** below and read ONLY the relevant documentation files.
4. Execute the task following [docs/04_TASK_TEMPLATE.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/04_TASK_TEMPLATE.md).

---

## Task Classification Matrix

Analyze the user's request and classify it into one of these categories:

### 1. UI / DESIGN TASK (Colors, Layout, Tailwind, Components)
- Read: [21_FRONTEND.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/21_FRONTEND.md), [23_UI_GUIDE.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/23_UI_GUIDE.md), [24_STYLE_GUIDE.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/24_STYLE_GUIDE.md).

### 2. FEATURE ADDITION (New module, functionality, workflow)
- Read: [09_FEATURES.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/09_FEATURES.md), [10_MODULES.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/10_MODULES.md), [14_SERVICES.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/14_SERVICES.md), [17_BUSINESS_LOGIC.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/17_BUSINESS_LOGIC.md).

### 3. DATABASE TASK (Columns, Migrations, Relationships, Tables)
- Read: [11_DATABASE.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/11_DATABASE.md), [12_MODELS.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/12_MODELS.md).

### 4. CONTROLLER TASK (Controller logic, Action methods)
- Read: [13_CONTROLLERS.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/13_CONTROLLERS.md), [14_SERVICES.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/14_SERVICES.md).

### 5. ROUTES TASK (Web/API Routes, Middleware, URLs)
- Read: [15_ROUTES.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/15_ROUTES.md).

### 6. AUTHENTICATION TASK (Login, Registration, Password reset, 2FA)
- Read: [18_AUTHENTICATION.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/18_AUTHENTICATION.md).

### 7. PERMISSIONS TASK (Roles, Permissions, Access control)
- Read: [19_AUTHORIZATION.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/19_AUTHORIZATION.md), [20_PERMISSIONS.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/20_PERMISSIONS.md).

### 8. API TASK (REST API, Webhook endpoints, Responses)
- Read: [16_API.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/16_API.md).

### 9. PERFORMANCE TASK (Slow query, Optimization, Caching)
- Read: [29_CODE_REVIEW.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/29_CODE_REVIEW.md), [11_DATABASE.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/11_DATABASE.md), [07_ARCHITECTURE.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/07_ARCHITECTURE.md).

### 10. BUG FIX TASK
- Identify affected module -> read corresponding module doc -> inspect ONLY target source files.

---

## Minimal Inspection Policy
- **Do NOT scan the entire project repository.**
- Inspect ONLY the source files relevant to the classified task.
- Treat documentation inside `docs/` as the primary source of truth.
