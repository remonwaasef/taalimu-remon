import { test, expect } from '@playwright/test';

test.describe('Payment & Subscription Flow', () => {
    test.beforeEach(async ({ page }) => {
        // Login as admin
        await page.goto('http://demo-center.localhost:8000/login');
        await page.fill('input[name="email"]', 'admin@demo.com');
        await page.fill('input[name="password"]', 'password');
        await page.click('button[type="submit"]');
        await expect(page).toHaveURL('http://demo-center.localhost:8000/');
    });

    test('Admin can navigate to subscription plans', async ({ page }) => {
        await page.goto('http://demo-center.localhost:8000/subscription');
        await expect(page.locator('body')).toContainText(/Subscription Plans|الباقات/);

        // Check if packages are listed (using h4 from view)
        await expect(page.locator('h4').first()).toBeVisible();
    });

    test('Admin redirected to login if session expires', async ({ page }) => {
        await page.context().clearCookies();
        await page.goto('http://demo-center.localhost:8000/');
        await expect(page).toHaveURL(/.*login/);
    });
});
