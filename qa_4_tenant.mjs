import { newBrowser, login, check, recordError, fetchAs, dumpJson, getResults } from './qa_helpers.mjs';

const { browser, page } = await newBrowser();
const T1 = 'http://demo-center.localhost:8000';
const T2 = 'http://ra3y.localhost:8000';

// ============ T1 ADMIN ACCESSES T2 HOST ============
try {
  await login(page, T1);
  await page.goto(T2 + '/login');
  await page.waitForTimeout(1500);
  const body = await page.locator('body').innerText();
  const isLogin = /تسجيل الدخول|Login|البريد الإلكتروني|Email/i.test(body);
  const isDashboard = body.includes('لوحة التحكم') || body.includes('Dashboard');
  check('T1 session does not cross into T2 (must prompt login)', isLogin && !isDashboard, page.url());
} catch (e) { recordError('cross-tenant session', e); }

// ============ T2 IDs IN T1 URLS ============
try {
  await login(page, T1);
  const probes = [
    ['/students/1', 'T2 student id 1 in T1'],
    ['/students/99999', 'nonexistent student'],
    ['/sales/1', 'T2 sale id 1 in T1'],
    ['/courses/1/edit', 'T2 course id 1 in T1'],
    ['/instructors/1/edit', 'T2 instructor id 1 in T1'],
  ];
  for (const [path, label] of probes) {
    const r = await fetchAs(page, T1 + path);
    const ok = [403, 404, 302].includes(r.status);
    check(label, ok, 'status=' + r.status);
  }
} catch (e) { recordError('T2 ids in T1', e); }

// ============ T1 API MUST NOT RETURN T2 DATA ============
try {
  await login(page, T1);
  const r = await fetchAs(page, T1 + '/students/search?q=ra3y');
  const leak = Array.isArray(r.body) && r.body.length > 0;
  check('T1 search does not leak T2 students', !leak, 'status=' + r.status + ' count=' + (Array.isArray(r.body) ? r.body.length : 'n/a'));
  const r2 = await fetchAs(page, T1 + '/students/search?q=');
  check('T1 empty search returns empty (no global leak)', Array.isArray(r2.body) && r2.body.length === 0, 'count=' + (Array.isArray(r2.body) ? r2.body.length : r2.status));
} catch (e) { recordError('API isolation', e); }

// ============ DIRECT T2 ACCESS AS GUEST ============
try {
  await page.goto(T2 + '/students');
  await page.waitForTimeout(1500);
  const body = await page.locator('body').innerText();
  const blocked = /تسجيل الدخول|Login|البريد/i.test(body) || page.url().includes('login');
  check('guest cannot reach T2 students (redirects to login)', blocked, page.url());
} catch (e) { recordError('guest T2', e); }

// ============ T1 DATA SHAPE SANITY ============
try {
  await login(page, T1);
  const r = await fetchAs(page, T1 + '/students/search?q=' + encodeURIComponent('طالب تجريبي'));
  const names = Array.isArray(r.body) ? r.body.map(s => s.text) : [];
  const allT1 = names.every(n => !n.includes('@ra3y') && !n.includes('ra3y'));
  check('T1 student results belong to T1', allT1, 'count=' + names.length);
} catch (e) { recordError('T1 shape', e); }

await dumpJson('tenant');
await browser.close();
const results = getResults();
console.log('TENANT ISOLATION DONE. Passed: ' + results.checks.filter(c => c.passed).length + '/' + results.checks.length);
