import { chromium } from '@playwright/test';
const browser = await chromium.launch();
const page = await browser.newPage();
page.setDefaultTimeout(20000);
for (const url of ['http://demo-center.localhost:8000/login', 'http://127.0.0.1:8000/login']) {
  try {
    const t0 = Date.now();
    const resp = await page.goto(url, { waitUntil: 'domcontentloaded' });
    console.log(url, '->', resp ? resp.status() : 'null', (Date.now() - t0) + 'ms');
  } catch (e) {
    console.log(url, '-> ERROR:', String(e).split('\n')[0]);
  }
}
await browser.close();