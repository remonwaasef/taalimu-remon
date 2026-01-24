import { test, expect } from '@playwright/test';

test.describe('Report Export Flow', () => {
    test.beforeEach(async ({ page }) => {
        // Login as admin
        await page.goto('http://demo-center.localhost:8000/login');
        await page.fill('input[name="email"]', 'admin@demo.com');
        await page.fill('input[name="password"]', 'password');
        await page.click('button[type="submit"]');
        await expect(page).toHaveURL('http://demo-center.localhost:8000/');
    });

    test('Admin can export students list to CSV', async ({ page }) => {
        // Go to students list
        await page.goto('http://demo-center.localhost:8000/students');

        // Click the export button we just added
        const downloadPromise = page.waitForEvent('download');
        await page.click('#export-students-btn');
        const download = await downloadPromise;

        // Wait for download to complete
        const path = await download.path();
        expect(path).toBeTruthy();
        expect(download.suggestedFilename()).toBe('students_export.csv');
    });
});
