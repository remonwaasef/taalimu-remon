import { newBrowser, fetchAs, check, getResults, dumpJson, recordError, adminLogin } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
const A = (p) => 'http://localhost:8000/admin' + p;

await adminLogin(page);

// 1. Create tenant
let newTenantId = null;
let newTenantDomain = 'qa-adm-' + Date.now() % 100000;
try {
  await page.goto(A('/tenants/create'), { waitUntil: 'domcontentloaded', timeout: 30000 });
  await page.waitForTimeout(1000);
  const r = await page.evaluate(async (domain) => {
    const tk = document.querySelector('meta[name=csrf-token]')?.content;
    const res = await fetch('/admin/tenants', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': tk, 'Accept': 'application/json' },
      body: new URLSearchParams({
        _token: tk,
        name: 'QA Admin Tenant',
        domain: domain,
        email: domain + '@example.com',
        status: 'active',
      }).toString(),
    });
    const txt = await res.text();
    let body = {};
    try { body = JSON.parse(txt); } catch { body = { raw: txt.slice(0, 200) }; }
    return { status: res.status, url: res.url, body };
  }, newTenantDomain);
  check('admin create tenant', r.status === 302 || r.status === 200 || r.status === 201, 'status=' + r.status + ' :: ' + JSON.stringify(r.body).slice(0, 150));
  const m = (r.url + JSON.stringify(r.body)).match(/tenants\/(\d+)/);
  if (m) newTenantId = m[1];
  if (!newTenantId) {
    const rl = await fetchAs(page, A('/tenants'), {});
    const t2 = typeof rl.body === 'string' ? rl.body : '';
    const mt = t2.match(/tenants\/(\d+)[^"]*qa-adm/);
    if (!mt) { const mi = t2.indexOf('qa-adm-' + (Date.now() % 100000)); }
    const m3 = [...t2.matchAll(/tenants\/(\d+)/g)];
    if (m3.length) newTenantId = m3[m3.length - 1][1];
  }
} catch (e) { recordError('admin create tenant', e); }

// 2. Tenant show for new tenant
try {
  if (newTenantId) {
    const r = await fetchAs(page, A('/tenants/' + newTenantId), {});
    check('admin tenant show (new)', r.status === 200, 'status=' + r.status);
  } else { check('admin tenant show (new)', false, 'no id'); }
} catch (e) { recordError('admin tenant show (new)', e); }

// 3. Update subscription of tenant 1 (trial extension)
try {
  await page.goto(A('/subscriptions/1/edit'), { waitUntil: 'domcontentloaded', timeout: 30000 });
  await page.waitForTimeout(1000);
  const r = await page.evaluate(async () => {
    const tk = document.querySelector('meta[name=csrf-token]')?.content;
    const res = await fetch('/admin/subscriptions/1', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': tk, 'Accept': 'application/json' },
      body: new URLSearchParams({ _token: tk, _method: 'PUT', status: 'active', billing_cycle: 'monthly', base_price: '450', total_amount: '450' }).toString(),
    });
    const txt = await res.text();
    return { status: res.status, url: res.url, body: txt.slice(0, 150) };
  });
  check('admin subscription update', r.status === 302 || r.status === 200, 'status=' + r.status + ' :: ' + r.body);
} catch (e) { recordError('admin subscription update', e); }

// 4. Roles: create custom role
try {
  await page.goto(A('/roles/create'), { waitUntil: 'domcontentloaded', timeout: 30000 });
  await page.waitForTimeout(1000);
  const r = await page.evaluate(async () => {
    const tk = document.querySelector('meta[name=csrf-token]')?.content;
    const res = await fetch('/admin/roles', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': tk, 'Accept': 'application/json' },
      body: new URLSearchParams({ _token: tk, name: 'QA Admin Role ' + Date.now() % 100000, guard_name: 'web' }).toString(),
    });
    const txt = await res.text();
    return { status: res.status, url: res.url, body: txt.slice(0, 150) };
  });
  check('admin role create', r.status === 302 || r.status === 200 || r.status === 201, 'status=' + r.status + ' :: ' + r.body);
} catch (e) { recordError('admin role create', e); }

// 5. Admin users: create global admin
try {
  await page.goto(A('/users/create'), { waitUntil: 'domcontentloaded', timeout: 30000 });
  await page.waitForTimeout(1000);
  const r = await page.evaluate(async () => {
    const tk = document.querySelector('meta[name=csrf-token]')?.content;
    const res = await fetch('/admin/users', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': tk, 'Accept': 'application/json' },
      body: new URLSearchParams({
        _token: tk,
        name: 'QA Admin User',
        email: 'qaadmin' + Date.now() % 100000 + '@example.com',
        password: 'password123',
        password_confirmation: 'password123',
        role: 'finance_manager',
      }).toString(),
    });
    const txt = await res.text();
    return { status: res.status, url: res.url, body: txt.slice(0, 150) };
  });
  check('admin user create', r.status === 302 || r.status === 200 || r.status === 201, 'status=' + r.status + ' :: ' + r.body);
} catch (e) { recordError('admin user create', e); }

// 6. Settings update (general tab, site name)
try {
  await page.goto(A('/settings'), { waitUntil: 'domcontentloaded', timeout: 30000 });
  await page.waitForTimeout(1000);
  const r = await page.evaluate(async () => {
    const tk = document.querySelector('meta[name=csrf-token]')?.content;
    const res = await fetch('/admin/settings', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': tk, 'Accept': 'application/json' },
      body: new URLSearchParams({ _token: tk, app_name: 'Taalimu QA', site_name: 'Taalimu QA', admin_email: 'admin@admin.com' }).toString(),
    });
    const txt = await res.text();
    return { status: res.status, url: res.url, body: txt.slice(0, 150) };
  });
  check('admin settings update', r.status === 302 || r.status === 200, 'status=' + r.status + ' :: ' + r.body);
} catch (e) { recordError('admin settings update', e); }

await dumpJson('admin2');
await browser.close();
const results = getResults();
console.log('ADMIN2 DONE. Passed: ' + results.checks.filter(c => c.passed).length + '/' + results.checks.length);