import { newBrowser, fetchAs, check, getResults, dumpJson, recordError, adminLogin } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
const A = (p) => 'http://localhost:8000/admin' + p;

await adminLogin(page);
await page.goto(A('/tenants'), { waitUntil: 'domcontentloaded', timeout: 30000 });
await page.waitForTimeout(1000);

// 1. Tenant toggle-status (deactivate then reactivate)
try {
  const r = await page.evaluate(async () => {
    const tk = document.querySelector('meta[name=csrf-token]')?.content;
    const res = await fetch('/admin/tenants/2/toggle-status', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': tk, 'Accept': 'application/json' },
      body: new URLSearchParams({ _token: tk }).toString(),
    });
    return { status: res.status, body: (await res.text()).slice(0, 150) };
  });
  check('tenant toggle-status', r.status === 302 || r.status === 200, 'status=' + r.status + ' :: ' + r.body);
  // toggle back so tenant 2 stays active
  if (r.status === 302 || r.status === 200) {
    await page.evaluate(async () => {
      const tk = document.querySelector('meta[name=csrf-token]')?.content;
      await fetch('/admin/tenants/2/toggle-status', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': tk, 'Accept': 'application/json' },
        body: new URLSearchParams({ _token: tk }).toString(),
      });
    });
  }
} catch (e) { recordError('tenant toggle-status', e); }

// 2. Subscription destroy unauthorized (non-super_admin should be blocked - role check)
try {
  const r = await fetchAs(page, A('/subscriptions/7'), { method: 'DELETE', headers: {} });
  check('subscription destroy (needs check)', r.status === 403 || r.status === 404 || r.status === 419, 'status=' + r.status);
} catch (e) { recordError('subscription destroy', e); }

// 3. Ticket reply flow (find first open ticket and reply)
try {
  const r = await fetchAs(page, A('/tickets'), {});
  const txt = typeof r.body === 'string' ? r.body : '';
  const m = txt.match(/tickets\/(\d+)/g);
  if (m && m.length) {
    const tid = m[0].split('/')[1];
    const r2 = await page.evaluate(async (id) => {
      const tk = document.querySelector('meta[name=csrf-token]')?.content;
      const res = await fetch('/admin/tickets/' + id + '/reply', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': tk, 'Accept': 'application/json' },
        body: new URLSearchParams({ _token: tk, message: 'QA reply test', status: 'open' }).toString(),
      });
      return { status: res.status, body: (await res.text()).slice(0, 150) };
    }, tid);
    check('ticket reply', r2.status === 302 || r2.status === 200, 'status=' + r2.status + ' :: ' + r2.body);
  } else {
    check('ticket reply (no tickets)', true, 'no tickets to reply to');
  }
} catch (e) { recordError('ticket reply', e); }

// 4. Backups: create backup
try {
  const r = await page.evaluate(async () => {
    const tk = document.querySelector('meta[name=csrf-token]')?.content;
    const res = await fetch('/admin/backups', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': tk, 'Accept': 'application/json' },
      body: new URLSearchParams({ _token: tk }).toString(),
    });
    return { status: res.status, body: (await res.text()).slice(0, 150) };
  });
  check('backup create', r.status === 302 || r.status === 200, 'status=' + r.status + ' :: ' + r.body);
} catch (e) { recordError('backup create', e); }

// 5. Impersonate tenant 1 (redirect to tenant domain) - LAST since it switches session
try {
  await page.goto(A('/tenants/1/impersonate'), { waitUntil: 'domcontentloaded', timeout: 30000 });
  await page.waitForTimeout(2500);
  const url = page.url();
  check('impersonate tenant', url.includes('.localhost'), 'url=' + url);
} catch (e) { recordError('impersonate tenant', e); }

await dumpJson('admin3');
await browser.close();
const results = getResults();
console.log('ADMIN3 DONE. Passed: ' + results.checks.filter(c => c.passed).length + '/' + results.checks.length);