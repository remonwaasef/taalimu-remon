import { newBrowser, fetchAs } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
await page.goto('http://localhost:8000/admin/login', { waitUntil: 'domcontentloaded', timeout: 30000 });
await page.waitForTimeout(1500);
const token = await page.evaluate(() => document.querySelector('meta[name=csrf-token]')?.content);
const r = await page.evaluate(async (tk) => {
  const res = await fetch('/admin/login', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': tk, 'Accept': 'text/html' },
    body: new URLSearchParams({ email: 'admin@admin.com', password: 'password', _token: tk }).toString(),
  });
  return { status: res.status, url: res.url, text: (await res.text()).slice(0, 400) };
}, token);
console.log('STATUS: ' + r.status + ' URL: ' + r.url);
console.log(r.text);
await browser.close();
