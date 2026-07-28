# README_AI - AI Operating System Manual

Welcome AI Assistant. This repository is structured as an **AI-First, Self-Documented Codebase**.

Before taking any action or analyzing source files, follow the instructions in this guide.

---

## 1. Session Initialization Sequence

Always follow this exact sequence:

1. **[PROJECT_MANIFEST.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/PROJECT_MANIFEST.md)**: Entry point in repository root detailing stack, versions, and core rules.
2. **[docs/00_AI_BOOT.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/00_AI_BOOT.md)**: AI session initialization rules, Task Classification Matrix, and minimal inspection policy.
3. **[docs/01_MASTER_CONTEXT.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/01_MASTER_CONTEXT.md)**: Standalone single source of truth reference (< 5000 words).
4. **Target Feature Docs**: Read ONLY the specific doc files related to the classified task type (UI, Database, Feature, Route, Controller, Auth, Permissions, API, Performance, Bug Fix).
5. **Target Source Files**: Inspect ONLY the specific code files requiring modification.
6. **Post-Task Updates**: Update documentation according to [docs/33_AFTER_EVERY_CHANGE.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/33_AFTER_EVERY_CHANGE.md).

---

## 2. Key AI Operating Principles

- **Minimal Code Inspection**: Do NOT scan the entire project. Use `docs/01_MASTER_CONTEXT.md` as your primary reference.
- **Preserve Architecture**: Do NOT alter the modular monolith structure (`Modules/*`), single-database multi-tenancy (`BelongsToTenant` trait), or domain service layer (`app/Services/`).
- **Reuse Existing Code**: Check [docs/26_PROJECT_MEMORY.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/26_PROJECT_MEMORY.md) before creating new helpers, components, or services.
- **Synchronize Docs**: Keep all documentation synchronized with the code after every completed task.

---

## 3. Standard Response Format

For every task, respond using this structure:

```markdown
### 1. Understanding
Summarize the task and expected outcome.

### 2. Documentation Read
List the specific docs/ files read.

### 3. Files To Inspect
List source files required.

### 4. Implementation Plan
Explain the step-by-step implementation.

### 5. Risks
Identify risks and mitigation plan.

### 6. Code Changes
Implement the code changes.

### 7. Verification
Explain how changes were verified.

### 8. Documentation Updates
List updated documentation files.

### 9. Notes
Any warnings, side effects, or future recommendations.
```
