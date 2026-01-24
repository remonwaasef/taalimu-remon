import { test, expect } from '@playwright/test';

test.describe('Attendance Tracking Flow', () => {
    test.beforeEach(async ({ page }) => {
        // Login as admin
        await page.goto('http://demo-center.localhost:8000/login');
        await page.fill('input[name="email"]', 'admin@demo.com');
        await page.fill('input[name="password"]', 'password');
        await page.click('button[type="submit"]');
        await expect(page).toHaveURL('http://demo-center.localhost:8000/');
    });

    test('Admin can view and mark attendance', async ({ page }) => {
        // Go to attendance index
        await page.goto('http://demo-center.localhost:8000/attendance');

        // Check if page header is correct
        await expect(page.locator('body')).toContainText(/تحضير الطلاب|الحضور/);

        // Navigate to a session (if any)
        const sessionLink = page.locator('a[href*="/attendance/schedule/"]').first();
        if (await sessionLink.isVisible()) {
            await sessionLink.click();

            // Exact text from show.blade.php
            await expect(page.locator('body')).toContainText('قائمة الطلاب المسجلين');

            // Mark a student as present
            // In the show view, there are individual buttons/forms for each status
            const presentButton = page.locator('button:has-text("حاضر")').first();
            await presentButton.click();

            // Check for success message
            await expect(page.locator('body')).toContainText(/بنجاح/);
        }
    });
});
