import { newBrowser, login, check, recordError, dumpJson, BASE } from './qa_helpers.mjs';

const { browser, page } = await newBrowser();

// ============ 1. Login flows ============
try {
  await page.goto(BASE + '/login');
  const hasTenantName = await page.locator('body').getByText('مركز الاختبار التجريبي').count();
  check('login page shows tenant name', hasTenantName > 0);
  const hasLoginTitle = await page.locator('body').getByText(/تسجيل الدخول|دخول/).count();
  check('login page has login form', hasLoginTitle > 0);
} catch (e) { recordError('login page load', e); }

// Empty login
try {
  await page.goto(BASE + '/login');
  await page.click('button[type="submit"]');
  await page.waitForTimeout(1200);
  const body = await page.locator('body').innerText();
  check('empty login blocked (validation msg)', /required|مطلوب|الحقل/i.test(body), body.slice(0, 120).replace(/\n/g, ' '));
} catch (e) { recordError('empty login', e); }

// Wrong password
try {
  await page.goto(BASE + '/login');
  await page.fill('input[name="email"]', 'admin@demo.com');
  await page.fill('input[name="password"]', 'wrongpass123');
  await page.click('button[type="submit"]');
  await page.waitForTimeout(1200);
  const body = await page.locator('body').innerText();
  const url = page.url();
  check('wrong password rejected', /credentials|بيانات|خطأ|incorrect/i.test(body) || url.includes('login'), url + ' :: ' + body.slice(0, 120).replace(/\n/g, ' '));
} catch (e) { recordError('wrong password', e); }

// Nonexistent user
try {
  await page.goto(BASE + '/login');
  await page.fill('input[name="email"]', 'nosuchuser@example.com');
  await page.fill('input[name="password"]', 'password');
  await page.click('button[type="submit"]');
  await page.waitForTimeout(1200);
  const body = await page.locator('body').innerText();
  const url = page.url();
  check('nonexistent user rejected', url.includes('login') || /خطأ|بيانات/i.test(body), url);
} catch (e) { recordError('nonexistent user', e); }

// Valid login
try {
  await login(page);
  const url = page.url();
  check('valid login lands on dashboard', /demo-center\.localhost:8000\/$/.test(url), url);
  const body = await page.locator('body').innerText();
  check('dashboard shows welcome text', /لوحة التحكم|مرحبا|مرحباً|إحصائيات/.test(body), body.slice(0, 100).replace(/\n/g, ' '));
} catch (e) { recordError('valid login', e); }

// Dashboard stats sanity
try {
  const statCards = await page.locator('h6, .card h6, .stat').count();
  check('dashboard renders stat cards', statCards >= 3, 'cards=' + statCards);
} catch (e) { recordError('dashboard stats', e); }

// Logout
try {
  await page.goto(BASE + '/logout').catch(() => {});
  await page.waitForTimeout(1000);
  const url = page.url();
  await page.goto(BASE + '/');
  await page.waitForTimeout(1500);
  check('logout redirects to login and protects dashboard', page.url().includes('/login'), page.url());
} catch (e) { recordError('logout', e); }

// Protected routes redirect unauthenticated
try {
  for (const path of ['/students', '/sales', '/attendance', '/courses', '/settings', '/analytics', '/billing', '/users']) {
    await page.goto(BASE + path, { waitUntil: 'commit' });
    await page.waitForTimeout(600);
    const url = page.url();
    check('unauthenticated ' + path + ' redirects to login', url.includes('/login'), url);
  }
} catch (e) { recordError('protected routes', e); }

// ============ 2. Register flow (public) ============
try {
  await page.goto('http://localhost:8000/register');
  await page.waitForTimeout(1000);
  const body = await page.locator('body').innerText();
  check('register page loads', /تسجيل|انشاء|إنشاء حساب|register/i.test(body), body.slice(0, 100).replace(/\n/g, ' '));
} catch (e) { recordError('register page', e); }

// ============ 3. Password reset pages ============
try {
  await page.goto('http://localhost:8000/password/reset');
  await page.waitForTimeout(1000);
  const body = await page.locator('body').innerText();
  check('forgot password page loads', /استعادة|كلمة المرور|البريد/i.test(body), body.slice(0, 100).replace(/\n/g, ' '));
} catch (e) { recordError('forgot password', e); }

// ============ 4. 2FA enable page ============
try {
  await login(page);
  await page.goto(BASE + '/2fa/setup');
  await page.waitForTimeout(1200);
  const body = await page.locator('body').innerText();
  check('2FA setup page loads', /2FA|two|توثيق|QR|كود/i.test(body), body.slice(0, 100).replace(/\n/g, ' '));
} catch (e) { recordError('2fa setup', e); }

await dumpJson('auth');
await browser.close();
const { getResults } = await import('./qa_helpers.mjs');
const results = getResults();
console.log('AUTH SUITE DONE. Passed: ' + results.checks.filter(c => c.passed).length + '/' + results.checks.length);
