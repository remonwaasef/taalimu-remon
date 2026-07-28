# 04_TASK_TEMPLATE - AI Task Execution Protocol

> **Purpose:** Defines the standard workflow and response structure that every AI assistant must follow before and after making changes to this project.

---

# Task Classification & Execution Workflow

For every request, follow this exact sequence:

1. **Classify Task**: Check [00_AI_BOOT.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/00_AI_BOOT.md) Task Classification Matrix (UI, Feature, DB, Controller, Route, Auth, Permissions, API, Performance, Bug Fix).
2. **Read Required Docs**: Read ONLY the specific documentation files linked to that task category.
3. **Inspect Minimal Files**: Open ONLY the source files necessary to fulfill the request.
4. **Pre-Coding Explanation**: Provide Understanding, Documentation Read, Files To Inspect, Implementation Plan, and Risks.
5. **Implement Code**: Write clean, architecture-preserving code following PSR-12 and thin controller rules.
6. **Verify Execution**: Run unit/feature tests and verify UI/permissions integrity.
7. **Post-Task Doc Update**: Update affected documentation files per [33_AFTER_EVERY_CHANGE.md](file:///d:/new%20project/antigravty/taalimu.com/taalimu.com/docs/33_AFTER_EVERY_CHANGE.md).

---

# Mandatory AI Response Structure

Every AI task response MUST follow this structure:

```markdown
### 1. Understanding
Summarize the task.

### 2. Documentation Read
List documentation files read.

### 3. Files To Inspect
List source files required.

### 4. Implementation Plan
Explain the implementation steps.

### 5. Risks
List potential risks and mitigation strategy.

### 6. Code Changes
Implement requested code.

### 7. Verification
Explain how changes were verified.

### 8. Documentation Updates
List updated documentation files.

### 9. Notes
Any warnings, side effects, or future recommendations.
```
