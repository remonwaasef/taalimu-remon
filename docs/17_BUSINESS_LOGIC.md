# 17_BUSINESS_LOGIC - Core Domain Rules & Diagrams

```mermaid
sequenceDiagram
    autonumber
    actor User as Center Owner
    participant Web as Web Controller / UI
    participant Service as TenantRegistrationService
    participant DB as MySQL Database
    
    User->>Web: Submit Registration Form
    Web->>Service: Call registerTenant($data)
    Service->>DB: Begin DB Transaction & Insert Tenant
    Service->>DB: Insert Admin User & Assign 'center_owner' Role
    Service->>DB: Initialize Default Site Settings & Stages/Grades
    Service->>DB: Commit Transaction
    Web-->>User: Redirect to Subdomain Dashboard
```

### Domain Rules
- **Multi-Tenant Scoping**: All queries auto-filtered by `tenant_id`.
- **Financial Transactions**: Invoices, sales, and payments must be wrapped in DB transactions.
- **Attendance Alerts**: Absent/Late attendance automatically triggers background WhatsApp notifications to parents.
