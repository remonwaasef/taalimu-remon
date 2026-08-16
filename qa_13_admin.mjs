import { newBrowser, fetchAs, check, getResults, dumpJson, recordError } from './qa_helpers.mjs';
import { createHmac } from 'node:crypto';
const { browser, page } = await newBrowser();
const ADMIN_BASE = 'http://localhost:8000';
const A = (p) => ADMIN_BASE + '/admin' + p;

function totp(secretB32, timestamp = Date.now()) {
  const alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
  let bits = '';
  for (const ch of secretB32) {
    const idx = alphabet.indexOf(ch);
    if (idx < 0) continue;
    bits += idx.toString(2).padStart(5, '0');
  }
  bits = bits.slice(0, Math.floor(bits.length / 8) * 8);
  const key = Buffer.from(bits.match(/.{8}/g).map(b => parseInt(b, 2)));
  const counter = Math.floor(timestamp / 30000);
  const buf = Buffer.alloc(8);
  buf.writeBigUInt64BE(BigInt(counter));
  const hmac = createHmac('sha1', key).update(buf).digest();
  const offset = hmac[hmac.length - 1] & 0x0f;
  const bin = ((hmac[offset] & 0x7f) << 24) | ((hmac[offset + 1] & 0xff) << 16) | ((hmac[offset + 2] & 0xff) << 8) | (hmac[offset + 3] & 0xff);
  return String(bin % 1000000).padStart(6, '0');
}

async function submitCode(code) {
  return page.evaluate(async (otp) => {
    const tk = document.querySelector('meta[name=csrf-token]')?.content;
    const res = await fetch('/admin/login/2fa', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': tk, 'Accept': 'text/html' },
      body: new URLSearchParams({ one_time_password: otp, _token: tk }).toString(),
    });
    return { status: res.status, url: res.url };
  }, code);
}

async function adminLogin() {
  await page.goto(A('/login'), { waitUntil: 'domcontentloaded', timeout: 30000 });
  await page.waitForTimeout(1500);
  const r = await page.evaluate(async () => {
    const tk = document.querySelector('meta[name=csrf-token]')?.content;
    const res = await fetch('/admin/login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': tk, 'Accept': 'text/html' },
      body: new URLSearchParams({ email: 'admin@admin.com', password: 'password', _token: tk }).toString(),
    });
    return { status: res.status, url: res.url };
  });
  if (r.url.includes('admin/login/2fa')) {
    await page.goto(A('/login/2fa'), { waitUntil: 'domcontentloaded', timeout: 30000 });
    await page.waitForTimeout(1000);
    return submitCode(totp('JBSWY3DPEHPK3PXP'));
  }
  return r;
}

// 1. Admin login page loads
try {
  await page.goto(A('/login'), { waitUntil: 'domcontentloaded', timeout: 30000 });
  await page.waitForTimeout(1500);
  check('admin login page', page.url().includes('/admin/login'), 'url=' + page.url());
} catch (e) { recordError('admin login page', e); }

// 2. Admin login with wrong password (rejected)
try {
  await page.goto(A('/login'), { waitUntil: 'domcontentloaded', timeout: 30000 });
  await page.waitForTimeout(1500);
  const r = await page.evaluate(async () => {
    const tk = document.querySelector('meta[name=csrf-token]')?.content;
    const res = await fetch('/admin/login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': tk, 'Accept': 'text/html' },
      body: new URLSearchParams({ email: 'admin@admin.com', password: 'wrongpass123', _token: tk }).toString(),
    });
    return { status: res.status, url: res.url };
  });
  check('admin login wrong password rejected', r.url.includes('/admin/login'), 'url=' + r.url);
} catch (e) { recordError('admin login wrong password', e); }

// 3. Admin login success
try {
  const r = await adminLogin();
  check('admin login success', r.url.includes('/admin') && !r.url.includes('/login'), 'url=' + r.url);
} catch (e) { recordError('admin login success', e); }

// 4. Dashboard
try {
  const r = await fetchAs(page, A('/'), {});
  check('admin dashboard', r.status === 200, 'status=' + r.status);
} catch (e) { recordError('admin dashboard', e); }

// 5. Tenants list
try {
  const r = await fetchAs(page, A('/tenants'), {});
  const txt = typeof r.body === 'string' ? r.body : '';
  check('admin tenants list', r.status === 200 && txt.includes('demo-center'), 'status=' + r.status + ' hasDemo=' + txt.includes('demo-center'));
} catch (e) { recordError('admin tenants list', e); }

// 6. Tenant show page
try {
  const r = await fetchAs(page, A('/tenants/1'), {});
  check('admin tenant show', r.status === 200, 'status=' + r.status);
} catch (e) { recordError('admin tenant show', e); }

// 7. Subscriptions list
try {
  const r = await fetchAs(page, A('/subscriptions'), {});
  check('admin subscriptions list', r.status === 200, 'status=' + r.status);
} catch (e) { recordError('admin subscriptions list', e); }

// 8. Activity logs
try {
  const r = await fetchAs(page, A('/activity-logs'), {});
  check('admin activity logs', r.status === 200, 'status=' + r.status);
} catch (e) { recordError('admin activity logs', e); }

// 9. Tickets list
try {
  const r = await fetchAs(page, A('/tickets'), {});
  check('admin tickets list', r.status === 200, 'status=' + r.status);
} catch (e) { recordError('admin tickets list', e); }

// 10. Admin users list
try {
  const r = await fetchAs(page, A('/users'), {});
  const txt = typeof r.body === 'string' ? r.body : '';
  check('admin users list', r.status === 200 && txt.includes('admin@admin.com'), 'status=' + r.status + ' hasAdmin=' + txt.includes('admin@admin.com'));
} catch (e) { recordError('admin users list', e); }

// 11. Roles list
try {
  const r = await fetchAs(page, A('/roles'), {});
  check('admin roles list', r.status === 200, 'status=' + r.status);
} catch (e) { recordError('admin roles list', e); }

// 12. Settings page
try {
  const r = await fetchAs(page, A('/settings'), {});
  check('admin settings page', r.status === 200, 'status=' + r.status);
} catch (e) { recordError('admin settings page', e); }

// 13. Backups page
try {
  const r = await fetchAs(page, A('/backups'), {});
  check('admin backups page', r.status === 200, 'status=' + r.status);
} catch (e) { recordError('admin backups page', e); }

await dumpJson('admin');
await browser.close();
const results = getResults();
console.log('ADMIN DONE. Passed: ' + results.checks.filter(c => c.passed).length + '/' + results.checks.length);