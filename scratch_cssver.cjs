const { chromium } = require('playwright');
(async () => {
    const browser = await chromium.launch();
    const page = await browser.newPage();
    await page.goto('http://demo-center.localhost:8000/login', { waitUntil: 'domcontentloaded' });
    const urls = await page.evaluate(() =>
        Array.from(document.querySelectorAll('link[rel="stylesheet"]')).map((l) => l.href).filter((h) => h.includes('hope-ui'))
    );
    console.log(urls.join('\n'));
    await browser.close();
})().catch((e) => { console.error('FAILED:', e.message); process.exit(1); });
