const { chromium } = require('@playwright/test');

(async () => {
    const browser = await chromium.launch();
    const page = await browser.newPage();
    const errors = [];
    page.on('pageerror', e => errors.push('PAGEERROR: ' + e.message));
    page.on('console', m => { if (m.type() === 'error') errors.push('CONSOLE: ' + m.text()); });

    await page.goto('http://127.0.0.1:8000/admin/login');
    await page.fill('input[name="email"]', 'admin@admin.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/admin', { timeout: 15000 });
    await page.goto('http://127.0.0.1:8000/admin/settings');
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(1500);

    await page.click('#plans-tab');
    await page.waitForTimeout(600);
    console.log('plans pane active:', await page.evaluate(() => document.getElementById('plans').classList.contains('active')));

    console.log('--- TEST A: language switcher inside #plans ---');
    const enBtn = page.locator('#plans button').filter({ hasText: 'EN' }).first();
    await enBtn.click();
    for (let i = 0; i < 10; i++) {
        await page.waitForTimeout(150);
        const s = await page.evaluate(() => {
            const el = document.querySelector('#plans input[name*="name_en"]');
            if (!el) return 'no en input';
            const p = el.closest('[x-show]');
            return { display: getComputedStyle(p).display, opacity: getComputedStyle(p).opacity };
        });
        console.log(`  t=${i * 150}ms`, JSON.stringify(s));
    }
    await page.waitForTimeout(2000);
    console.log('  EN input after 3.5s:', JSON.stringify(await page.evaluate(() => {
        const el = document.querySelector('#plans input[name*="name_en"]');
        if (!el) return 'no en input';
        const p = el.closest('[x-show]');
        return { display: getComputedStyle(p).display, opacity: getComputedStyle(p).opacity };
    })));

    console.log('--- TEST B: Add Package modal ---');
    await page.click('button[data-bs-target="#addPackageModal"]', { timeout: 5000 });
    for (let i = 0; i < 16; i++) {
        await page.waitForTimeout(250);
        const s = await page.evaluate(() => {
            const el = document.getElementById('addPackageModal');
            return { cls: el.className, shown: el.classList.contains('show'), display: getComputedStyle(el).display };
        });
        console.log(`  t=${i * 250}ms`, JSON.stringify(s));
    }
    await page.screenshot({ path: 'scratch_modal_open.png' });

    console.log('--- TEST C: accordion inside #plans ---');
    const accBtn = page.locator('#plans button[data-bs-toggle="collapse"]').first();
    await accBtn.click().catch(e => console.log('  acc click fail:', e.message.split('\n')[0]));
    await page.waitForTimeout(400);
    console.log('  open accordion bodies in #plans:', await page.locator('#plans .accordion-collapse.show').count());
    await page.waitForTimeout(2000);
    console.log('  after 2.4s:', await page.locator('#plans .accordion-collapse.show').count());

    console.log('--- JS ERRORS ---');
    console.log(errors.length ? errors.join('\n') : 'none');
    await browser.close();
})();
