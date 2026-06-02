import { test, expect } from '@playwright/test';

test.describe('Authentication Flow', () => {
    test('User can login to the center dashboard', async ({ page }) => {
        // We use a subdomain for the tenant
        // In local development, demo-center.localhost should point to 127.0.0.1
        await page.goto('http://demo-center.localhost:8000/login');

        // Check if we are on the login page in Arabic
        await expect(page.locator('body')).toContainText('مركز الاختبار التجريبي');
        await expect(page.locator('body')).toContainText('تسجيل الدخول');

        // Fill credentials
        const email = process.env.TEST_EMAIL || 'admin@demo.com';
        const password = process.env.TEST_PASSWORD || 'password';
        await page.fill('input[name="email"]', email);
        await page.fill('input[name="password"]', password);

        // Click login
        await page.click('button[type="submit"]');

        // Verify redirection to dashboard
        // The dashboard should have specific Arabic text
        await expect(page).toHaveURL(/.*dashboard|.*center/);
        await expect(page.locator('body')).toContainText('لوحة التحكم');
    });

    test('Unauthorized user is redirected to login', async ({ page }) => {
        await page.goto('http://demo-center.localhost:8000/');
        await expect(page).toHaveURL(/.*login/);
    });
});
