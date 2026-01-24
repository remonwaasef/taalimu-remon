import { test, expect } from '@playwright/test';

test.describe('Course Enrollment Flow', () => {
    test.beforeEach(async ({ page }) => {
        // Login as admin
        await page.goto('http://demo-center.localhost:8000/login');
        await page.fill('input[name="email"]', 'admin@demo.com');
        await page.fill('input[name="password"]', 'password');
        await page.click('button[type="submit"]');
        await expect(page).toHaveURL('http://demo-center.localhost:8000/');
    });

    test('Admin can enroll a student in a course', async ({ page }) => {
        // Go to course page
        await page.goto('http://demo-center.localhost:8000/courses/1');

        // Open enrollment modal
        await page.click('button[data-bs-target="#enrollStudentModal"]');

        // Wait for modal to be visible
        const modal = page.locator('#enrollStudentModal');
        await expect(modal).toBeVisible();

        // Select a student
        await page.locator('.ts-control').click();
        await page.locator('.ts-dropdown .option').first().click();

        // Submit enrollment
        await page.click('#enrollStudentModal button[type="submit"]');

        // Check for success message
        await expect(page.locator('body')).toContainText(/بنجاح/);
    });
});
