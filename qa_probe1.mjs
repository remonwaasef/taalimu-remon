import { chromium } from '@playwright/test';
const browser = await chromium.launch();
const page = await browser.newPage();
page.setDefaultTimeout(15000);
try {
  const resp = await page.goto('http://demo-center.localhost:8000/login', { waitUntil: 'load' });
  console.log('STATUS:', resp.status(), 'URL:', page.url());
  const title = await page.title();
  console.log('TITLE:', title);
  const body = (await page.locator('body').innerText()).slice(0, 200);
  console.log('BODY:', JSON.stringify(body));
} catch (e) {
  console.log('ERROR:', String(e).slice(0, 500));
}
await browser.close();
