const { chromium } = require('playwright');

const BASE = 'https://demo-center.taalimu.com';
const PASS = 'password';

const cases = [
  { label: 'Center Admin', email: 'admin@demo.com', expectUrl: '/', expectText: null },
  { label: 'Instructor', email: 'instructor1@demo.com', expectUrl: '/instructor', expectText: null },
  { label: 'Student', email: 'student1@demo.com', expectUrl: '/campus', expectText: null },
  { label: 'Parent', email: 'parent@demo.com', expectUrl: '/parent', expectText: 'وليد رمضان' },
];

(async () => {
  const browser = await chromium.launch();
  const results = [];

  for (const c of cases) {
    const ctx = await browser.newContext();
    const page = await ctx.newPage();
    try {
      await page.goto(`${BASE}/login`, { waitUntil: 'networkidle' });
      await page.fill('input[name=email]', c.email);
      await page.fill('input[name=password]', PASS);
      await Promise.all([
        page.waitForURL((u) => u.pathname.startsWith(c.expectUrl), { timeout: 20000 }),
        page.click('button[type=submit]'),
      ]);
      let textCheck = true;
      if (c.expectText) {
        await page.waitForTimeout(1500);
        textCheck = (await page.content()).includes(c.expectText);
      }
      results.push(`PASS ${c.label}: ${page.url()} textOk=${textCheck}`);
    } catch (e) {
      results.push(`FAIL ${c.label}: ${e.message.split('\n')[0]}`);
    }
    await ctx.close();
  }

  const parentCtx = await browser.newContext();
  const p = await parentCtx.newPage();
  await p.goto(`${BASE}/login`, { waitUntil: 'networkidle' });
  await p.fill('input[name=email]', 'parent@demo.com');
  await p.fill('input[name=password]', PASS);
  await Promise.all([p.waitForURL((u) => u.pathname.startsWith('/parent'), { timeout: 20000 }), p.click('button[type=submit]')]);
  for (const path of ['/parent/courses', '/parent/schedule', '/parent/attendance', '/parent/finances', '/parent/profile']) {
    try {
      await p.goto(`${BASE}${path}`, { waitUntil: 'networkidle' });
      const title = await p.title();
      results.push(`PASS ${path}: title="${title}"`);
    } catch (e) {
      results.push(`FAIL ${path}: ${e.message.split('\n')[0]}`);
    }
  }
  await parentCtx.close();
  await browser.close();
  console.log(results.join('\n'));
})();