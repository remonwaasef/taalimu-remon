import { newBrowser, check, getResults, dumpJson, recordError } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
page.setDefaultTimeout(30000);

const T1 = 'http://demo-center.localhost:8000';
const T3 = 'http://qa-center-194003.localhost:8000';

// Seed used same random data for new tenants — check tenant 3 scope
try {
  // Login as T3 owner
  await page.goto(T3 + '/login');
  await page.waitForTimeout(1500);
  const email = 'qa.owner194003@example.com';
  const pw = 'password';
  await page.fill('input[name="email"]', email).catch(() => {});
  await page.fill('input[name="password"]', pw).catch(() => {});
  await Promise.all([
    page.waitForNavigation({ waitUntil: 'commit', timeout: 30000 }).catch(() => {}),
    page.click('button[type="submit"]').catch(() => {}),
  ]);
  await page.waitForTimeout(2000);
  const url = page.url();
  check('T3 login works', url.includes('dashboard') || url === T3 + '/' || url === T3, url);

  // Check T3 student list ONLY shows T3 students (isolation)
  const r = await page.evaluate(async () => {
    const res = await fetch('/students', { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
    return { status: res.status, text: await res.text() };
  });
  const t3HasOwn = r.text.includes('نور الدين');
  const t3HasT1Only = r.text.includes('أحمد خالد') || r.text.includes('سارة');
  check('T3 sees own students', t3HasOwn, 'status=' + r.status);
  check('T3 does NOT see T1 students', !t3HasT1Only, 'status=' + r.status);
  console.log('T3 students snippet:', r.text.slice(0, 220).replace(/\n+/g, ' '));
} catch (e) { recordError('T3 isolation', e); }

// Cross-tenant: T1 admin tries to access T3 domain
try {
  const { page: p2 } = await newBrowser();
  await p2.goto(T1 + '/login');
  await p2.waitForTimeout(1500);
  await p2.fill('input[name="email"]', 'admin@demo.com').catch(() => {});
  await p2.fill('input[name="password"]', 'password').catch(() => {});
  await Promise.all([
    p2.waitForNavigation({ waitUntil: 'commit', timeout: 30000 }).catch(() => {}),
    p2.click('button[type="submit"]').catch(() => {}),
  ]);
  await p2.waitForTimeout(1500);
  // Try to access T3 domain as T1 user
  await p2.goto(T3 + '/dashboard');
  await p2.waitForTimeout(2500);
  const fUrl = p2.url();
  const fBody = await p2.locator('body').innerText();
  const blocked = fUrl.includes('/login') || fBody.includes('تسجيل الدخول') || fBody.includes('unau');
  check('T1 user blocked from T3 domain', blocked, fUrl + ' :: ' + fBody.slice(0, 150).replace(/\n+/g, ' | '));
  await p2.close();
  // restore base page
} catch (e) { recordError('cross-tenant access', e); }

await dumpJson('iso');
await browser.close();
const results = getResults();
console.log('ISO DONE. Passed: ' + results.checks.filter(c => c.passed).length + '/' + results.checks.length);