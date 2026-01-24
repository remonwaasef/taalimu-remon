# Implement Plan Selection Plan

## Goal
Allow users to select a plan from the landing page, which will pre-select that plan on the registration form.

## Proposed Changes

### 1. Landing Page Links
#### [MODIFY] [resources/views/landing/partials/pricing.blade.php](file:///d:/new%20project/antigravty/edu/edu/resources/views/landing/partials/pricing.blade.php)
- Update CTA buttons to include `?plan=free`, `?plan=basic`, or `?plan=pro`.

### 2. Registration View
#### [MODIFY] [resources/views/auth/register.blade.php](file:///d:/new%20project/antigravty/edu/edu/resources/views/auth/register.blade.php)
- Add a hidden input field: `<input type="hidden" name="plan" value="{{ request('plan', 'free') }}">`
- Add a Plan Selection UI (Radio Cards) at the top of the form to show the selected plan and allow changing it.
    -   Free
    -   Basic
    -   Pro

### 3. Registration Logic
#### [MODIFY] [app/Http/Controllers/RegistrationController.php](file:///d:/new%20project/antigravty/edu/edu/app/Http/Controllers/RegistrationController.php)
- Update `register` method validation to include `plan`.
- Update the subscription creation logic to use the selected `plan` instead of hardcoded defaults.
    -   Map 'free', 'basic', 'pro' to appropriate initial subscription settings.

## Verification Plan

### Manual Verification
1.  **Click "Start Free Trial"**: Validate URL becomes `/register?plan=free` and "Free" is selected.
2.  **Click "Get Basic"**: Validate URL becomes `/register?plan=basic` and "Basic" is selected.
3.  **Click "Get Pro"**: Validate URL becomes `/register?plan=pro` and "Pro" is selected.
4.  **Complete Registration**: Verify the created tenant has the correct plan type in the database.
