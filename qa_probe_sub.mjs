import { newBrowser } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
await page.goto('http://localhost:8000/admin/login', { waitUntil: 'domcontentloaded', timeout: 30000 });
await page.waitForTimeout(1500);
await page.evaluate(async () => {
  const tk = document.querySelector('meta[name=csrf-token]')?.content;
  await fetch('/admin/login', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': tk, 'Accept': 'text/html' },
    body: new URLSearchParams({ email: 'admin@admin.com', password: 'password', _token: tk }).toString(),
  });
});
await page.waitForTimeout(1000);
await page.goto('http://localhost:8000/admin/subscriptions/1/edit', { waitUntil: 'domcontentloaded', timeout: 30000 });
await page.waitForTimeout(1500);
const r1 = await page.evaluate(async () => {
  const tk = document.querySelector('meta[name=csrf-token]')?.content;
  const res = await fetch('/admin/subscriptions/1', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': tk, 'Accept': 'application/json' },
    body: new URLSearchParams({ _token: tk, _method: 'PUT', status: 'active', billing_cycle: 'monthly', base_price: '450', total_amount: '450' }).toString(),
  });
  return { status: res.status, body: (await res.text()).slice(0, 200) };
});
console.log('POST+_method: ' + r1.status + ' :: ' + r1.body);
await browser.close();
