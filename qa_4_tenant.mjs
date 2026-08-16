import { newBrowser, login, check, recordError, fetchAs, dumpJson, getResults } from './qa_helpers.mjs';

const { browser, page } = await newBrowser();
const T1 = 'http://demo-center.localhost:8000';
const T3 = 'http://qa-center-194003.localhost:8000';

// ============ T1 ADMIN ACCESSES T3 HOST ============
try {
  await login(page, T1);
  await page.goto(T3 + '/login');
  await page.waitForTimeout(1500);
  const body = await page.locator('body').innerText();
  const isLogin = /تسجيل الدخول|Login|البريد/i.test(body);
  const isDashboard = body.includes('متابعة الحضور') || body.includes('الهيكل الأكاديمي');
  check('T1 session does not cross into T3 (must prompt login)', isLogin && !isDashboard, page.url());
} catch (e) { recordError('cross-tenant session', e); }

// ============ T3-ONLY IDs IN T1 URLS (must 404) ============
try {
  await login(page, T1);
  const probes = [
    ['/students/24', 'T3 student 24 in T1'],
    ['/students/28', 'T3 student 28 in T1'],
    ['/sales/28', 'T3 sale 28 in T1'],
    ['/courses/3/edit', 'T3 course 3 in T1'],
    ['/instructors/5/edit', 'T3 instructor 5 in T1'],
    ['/students/99999', 'nonexistent student'],
  ];
  for (const [path, label] of probes) {
    const r = await fetchAs(page, T1 + path);
    const ok = [403, 404, 302].includes(r.status);
    check(label, ok, 'status=' + r.status);
  }
} catch (e) { recordError('T3 ids in T1', e); }

// ============ T1 API MUST NOT RETURN T3 DATA ============
try {
  await login(page, T1);
  const r = await fetchAs(page, T1 + '/students/search?q=' + encodeURIComponent('فاطمة حسن'));
  const leak = Array.isArray(r.body) && r.body.length > 0;
  check('T1 search does not leak T3 students', !leak, 'status=' + r.status + ' count=' + (Array.isArray(r.body) ? r.body.length : 'n/a'));
  const r2 = await fetchAs(page, T1 + '/students/search?q=');
  check('T1 empty search returns empty (no global leak)', Array.isArray(r2.body) && r2.body.length === 0, 'count=' + (Array.isArray(r2.body) ? r2.body.length : r2.status));
} catch (e) { recordError('API isolation', e); }

// ============ DIRECT T3 ACCESS AS GUEST ============
try {
  await page.goto(T3 + '/students');
  await page.waitForTimeout(1500);
  const body = await page.locator('body').innerText();
  const blocked = /تسجيل الدخول|Login|البريد/i.test(body) || page.url().includes('login');
  check('guest cannot reach T3 students (redirects to login)', blocked, page.url());
} catch (e) { recordError('guest T3', e); }

// ============ T1 DATA SHAPE SANITY ============
try {
  await login(page, T1);
  const r = await fetchAs(page, T1 + '/students/search?q=' + encodeURIComponent('طالب تجريبي'));
  const names = Array.isArray(r.body) ? r.body.map(s => s.text) : [];
  const allT1 = names.length > 0 && names.every(n => !n.includes('@') || n.includes('demo-center'));
  check('T1 student results belong to T1', allT1, 'count=' + names.length);
} catch (e) { recordError('T1 shape', e); }

await dumpJson('tenant');
await browser.close();
const results = getResults();
console.log('TENANT ISOLATION DONE. Passed: ' + results.checks.filter(c => c.passed).length + '/' + results.checks.length);