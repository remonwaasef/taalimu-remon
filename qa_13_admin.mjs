import { newBrowser, fetchAs, check, getResults, dumpJson, recordError } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
const ADMIN_BASE = 'http://localhost:8000';
const A = (p) => ADMIN_BASE + '/admin' + p;

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