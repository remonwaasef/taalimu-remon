const { chromium } = require('@playwright/test');

(async () => {
    const browser = await chromium.launch();
    const context = await browser.newContext({ viewport: { width: 1440, height: 900 }, recordVideo: { dir: 'scratch_video2' } });
    const page = await context.newPage();
    const errors = [];
    page.on('pageerror', e => errors.push('PAGEERROR: ' + e.message));
    page.on('console', m => { if (m.type() === 'error') errors.push('CONSOLE: ' + m.text()); });

    await page.goto('https://taalimu.com/admin/login', { timeout: 30000 });
    await page.fill('input[name="email"]', 'admin@admin.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/admin', { timeout: 20000 });
    await page.goto('https://taalimu.com/admin/settings', { timeout: 30000 });
    await page.waitForLoadState('networkidle').catch(() => {});
    await page.waitForTimeout(1500);

    await page.click('#plans-tab');
    await page.waitForTimeout(800);

    console.log('--- TEST A: language EN button in first card ---');
    const enBtn = page.locator('#plans button').filter({ hasText: 'EN' }).first();
    await enBtn.click();
    for (let i = 0; i < 10; i++) {
        await page.waitForTimeout(200);
        const s = await page.evaluate(() => {
            const el = document.querySelector('#plans input[name*="name_en"]');
            if (!el) return 'no en input';
            const p = el.closest('[x-show]');
            return { display: getComputedStyle(p).display, opacity: getComputedStyle(p).opacity };
        });
        console.log(`  t=${i * 200}ms`, JSON.stringify(s));
    }

    console.log('--- TEST B: accordions (marketing & limits) ---');
    const accordionBtns = await page.locator('#plans button[data-bs-toggle="collapse"]').count();
    console.log('  accordion buttons in #plans:', accordionBtns);
    const firstAcc = page.locator('#plans button[data-bs-toggle="collapse"]').first();
    console.log('  first acc text:', (await firstAcc.innerText()).trim().slice(0, 60));
    await firstAcc.click();
    for (let i = 0; i < 12; i++) {
        await page.waitForTimeout(250);
        const open = await page.locator('#plans .accordion-collapse.show').count();
        const s = await page.evaluate(() => {
            const els = document.querySelectorAll('#plans .accordion-collapse');
            return [...els].map(e => ({ id: e.id, cls: e.className }));
        });
        console.log(`  t=${i * 250}ms openCount=${open}`, JSON.stringify(s));
    }

    console.log('--- JS ERRORS ---');
    console.log(errors.length ? errors.join('\n') : 'none');
    await page.screenshot({ path: 'scratch_prod_acc.png' });
    await context.close();
})();
