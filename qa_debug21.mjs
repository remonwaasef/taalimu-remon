import { newBrowser, BASE } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
page.setDefaultTimeout(40000);
page.on('requestfailed', (r) => console.log('FAILED:', r.url().slice(0, 120), r.failure()?.errorText));
page.on('response', (r) => { if (r.status() >= 400) console.log('HTTP', r.status(), r.url().slice(0, 120)); });
const t0 = Date.now();
await page.goto(BASE + '/login', { waitUntil: 'load', timeout: 40000 });
console.log('load OK in', Date.now() - t0, 'ms, url:', page.url());
await browser.close();