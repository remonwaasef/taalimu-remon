const { chromium } = require('@playwright/test');
const path = require('path');

(async () => {
    const browser = await chromium.launch();
    const page = await browser.newPage();

    const errors = [];
    page.on('pageerror', e => errors.push('PAGEERROR: ' + e.message));
    page.on('console', m => { if (m.type() === 'error') errors.push('CONSOLE: ' + m.text()); });

    const url = 'file:///' + path.resolve(__dirname, 'scratch_test_page.html').replace(/\\/g, '/');
    await page.goto(url);
    await page.waitForTimeout(1500);

    // Test 1: language switcher with opacity polling
    console.log('AR visible initially:', await page.isVisible('#input-ar'));
    console.log('EN visible initially:', await page.isVisible('#input-en'));
    await page.click('#btn-en');
    const samples = [];
    for (let i = 0; i < 10; i++) {
        await page.waitForTimeout(100);
        samples.push(await page.$eval('#input-en', el => ({
            visible: el.offsetParent !== null,
            opacity: getComputedStyle(el).opacity,
            cls: el.className
        })));
    }
    console.log('EN input timeline (100ms steps):', JSON.stringify(samples));

    // Test 2: modal open then auto-close check
    await page.click('#open-modal');
    for (let i = 0; i < 8; i++) {
        await page.waitForTimeout(300);
        console.log(`modal ${i * 300}ms:`, await page.$eval('#addPackageModal', el => ({
            cls: el.className,
            visible: el.offsetParent !== null
        })));
    }

    // close modal via backdrop click simulation
    await page.keyboard.press('Escape');
    await page.waitForTimeout(400);
    console.log('modal after Escape:', await page.$eval('#addPackageModal', el => el.className));

    // Test 3: accordion
    await page.click('#acc-btn', { timeout: 5000 }).catch(e => console.log('acc click fail:', e.message.split('\n')[0]));
    await page.waitForTimeout(500);
    console.log('accordion body visible:', await page.isVisible('#acc-body'));

    console.log('--- JS ERRORS ---');
    console.log(errors.length ? errors.join('\n') : 'none');
    await browser.close();
})();
