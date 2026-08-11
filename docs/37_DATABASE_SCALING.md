# 37_DATABASE_SCALING - Enterprise Database Partitioning Evaluation

> **Purpose**: Full technical evaluation of partitioning Taalimu's tenant tables by `tenant_id`, based on a live audit of the actual schema, with an explicit engineering recommendation.

---

## 1. Live Audit Results (2026-08-11)

| Metric | Value |
|---|---|
| Local server | **MariaDB 10.4.32** |
| Production (KVM) | MariaDB via `apt install mariadb-server` (Ubuntu LTS → 10.6/10.11) |
| Docker (dev-only) | `mysql:8.0` |
| Tables with `tenant_id` | **51** |
| Tables with foreign keys | **~56** |
| Partitioned tables today | 0 |
| Existing optimization | Composite indexes `(tenant_id, created_at, deleted_at)` on 20+ tables (migration `2026_07_10_000001_add_performance_indexes`) |

---

## 2. Constraint Analysis — Can We Partition by `tenant_id`?

### ❌ Blocking Constraint 1: Unique-Key Rule
MySQL/MariaDB require **every unique key on a partitioned table to include the partition key**.
All 51 tables use `PRIMARY KEY (id)` → partitioning by `tenant_id` forces a rewrite to
`PRIMARY KEY (tenant_id, id)` on every table, which:
- Breaks every foreign key referencing `id` (FK target columns must match exactly).
- Breaks auto-increment semantics (InnoDB requires the auto-increment column to be leftmost in a key).
- Breaks Eloquent `find()`, route-model binding, and API assumptions globally.

### ❌ Blocking Constraint 2: Foreign Keys on Partitioned Tables
- **MySQL 8.0**: FKs are **forbidden** on partitioned tables.
- **MariaDB 10.4 (current local server)**: FKs are **forbidden** on partitioned tables (support landed in 10.6).
- ~56 tables carry foreign keys — partitioning by `tenant_id` would mean **dropping referential integrity** on the financial, attendance, and enrollment domains. Unacceptable risk for a production billing system.

### ❌ Constraint 3: Rebuild Cost
`ALTER TABLE ... PARTITION BY` triggers a **full table rebuild with metadata locks**. On enterprise-scale tables this is maintenance-window downtime, not a live migration.

### ❌ Constraint 4: Partition-Count Ceiling
Practical ceiling ≈ 1,024–8,192 partitions. One partition per tenant caps the platform at roughly **1k–8k tenants** — this strategy actively *reduces* scalability toward the stated millions-of-users mission.

### ❌ Constraint 5: Negligible Pruning Benefit
All hot queries are already tenant-scoped (`TenantScope` forces `tenant_id` in `WHERE`) and served by composite B-tree indexes → O(log n) lookup. Partitioning-by-tenant only pays off when an index exceeds memory, which is not this system's bottleneck profile.

---

## 3. Engineering Verdict

> **Do NOT partition by `tenant_id`.** It is technically infeasible today (FK + unique-key constraints), operationally risky (rebuilds, integrity loss), and strategically self-limiting (partition-count ceiling).

The current composite-index strategy is the correct short/medium-term answer. Partitioning is only valuable as a **time-based retention mechanism** on append-heavy, FK-free tables.

---

## 4. Recommended Enterprise Scaling Path

### Phase A — Zero-Risk Hardening (Recommended now)
1. **Standardize on MariaDB 10.6+ (LTS) in production** — first MariaDB release supporting FKs on partitioned tables; unblocks Phase B and future options. (KVM: add MariaDB 10.6/10.11 from the official MariaDB repo instead of Ubuntu default 10.4-era packages.)
2. **Align docker-compose `db` service with production** (`mariadb:10.11` instead of `mysql:8.0`) so dev/prod behave identically.
3. **Enable slow-query logging + table-size monitoring** (weekly cron → Telegram report) so partitioning decisions are driven by real growth data, not guesses.

### Phase B — Time-Based RANGE Partitioning (When Volume Justifies)
Apply only to **append-heavy tables with no foreign keys**:
- **`activity_log`** (0 FKs, tenant-independent) → `PARTITION BY RANGE (TO_DAYS(created_at))` monthly, PK becomes `(id, created_at)` — enables **instant archival via `DROP PARTITION`** instead of slow `DELETE`s. Feasible on the current server.
- After the MariaDB 10.6+ upgrade: candidates for `(id, created_at)` PK + monthly ranges: `attendances`, `quiz_attempts`, `lesson_progress`, `payments`, `notifications`, `issue_timeline` — **each requires its FKs to be re-added under 10.6 and a zero-downtime migration plan**.

### Phase C — Enterprise Accounts (Documented Boundary)
- Tenant-per-database or tenant-per-instance hosting for enterprise clients that mandate physical separation (this remains the *real* answer to the ADR-001 limitation).

### Phase D — Millions-Scale Horizon
- Application-level **sharding by `tenant_id % N`** across database instances — an architectural program, not a per-table ALTER.

---

## 5. Explicitly NOT Recommended
- `PARTITION BY LIST (tenant_id)` / `HASH (tenant_id)` on any current table.
- Dropping foreign keys to enable partitioning.
- One-partition-per-tenant designs (hard ceiling).

---

## 6. Action Items (Checked = Done)
- [x] Live schema/volume audit (row counts, sizes, engines, FKs, partition state).
- [x] Phase A-2: docker-compose `mariadb:10.11` alignment (dev/prod parity).
- [x] Phase A-3: `db:monitor-sizes` weekly Telegram report with growth thresholds (scheduled Sunday 07:00).
- [ ] Phase A-1: production MariaDB 10.6+ standardization (KVM — deployment task).
- [ ] Phase B (triggered at ~50M rows on a candidate table): RANGE partitioning program with migration plan.
