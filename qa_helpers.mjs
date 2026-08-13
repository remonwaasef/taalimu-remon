import { chromium } from '@playwright/test';

export const BASE = 'http://demo-center.localhost:8000';
export const ADMIN = { email: 'admin@demo.com', password: 'password' };

export const results = { checks: [], errors: [] };
export const getResults = () => results;
export function check(name, passed, detail = '') {
  results.checks.push({ name, passed: !!passed, detail });
  const mark = passed ? 'PASS' : 'FAIL';
  console.log(`[${mark}] ${name}${detail ? ' :: ' + detail : ''}`);
}
export function recordError(where, err) {
  results.errors.push({ where, err: String(err).slice(0, 400) });
  console.log(`[ERROR] ${where} :: ${String(err).slice(0, 400)}`);
}

export async function newBrowser() {
  const browser = await chromium.launch();
  const ctx = await browser.newContext();
  const page = await ctx.newPage();
  page.setDefaultTimeout(30000);
  const consoleErrors = [];
  page.on('console', (m) => { if (m.type() === 'error') consoleErrors.push(m.text().slice(0, 250)); });
  page.on('pageerror', (e) => consoleErrors.push('PAGEERROR: ' + e.message.slice(0, 250)));
  page.on('requestfailed', (r) => consoleErrors.push('REQFAIL: ' + r.url().slice(0, 160) + ' ' + (r.failure()?.errorText || '')));
  page._consoleErrors = consoleErrors;
  return { browser, ctx, page };
}

export async function login(page, base = BASE, creds = ADMIN) {
  await page.goto(base + '/login', { timeout: 45000 });
  const emailInput = page.locator('input[name="email"]');
  await emailInput.first().waitFor({ state: 'attached', timeout: 8000 }).catch(() => {});
  if (!(await emailInput.count())) {
    await page.waitForTimeout(1200);
    return;
  }
  await emailInput.first().fill(creds.email);
  await page.fill('input[name="password"]', creds.password);
  await Promise.all([
    page.waitForNavigation({ waitUntil: 'commit', timeout: 30000 }).catch(() => {}),
    page.click('button[type="submit"]'),
  ]);
  await page.waitForTimeout(1200);
}

export async function fetchAs(page, url, opts = {}) {
  const cookies = await page.context().cookies();
  const xsrf = cookies.find(c => c.name === 'XSRF-TOKEN');
  const headers = {
    'Accept': 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
    ...(opts.headers || {}),
  };
  if (xsrf && !headers['X-XSRF-TOKEN']) {
    headers['X-XSRF-TOKEN'] = decodeURIComponent(xsrf.value);
  }
  return await page.evaluate(async ({ url, opts, headers }) => {
    try {
      const r = await fetch(url, {
        method: opts.method || 'GET',
        headers,
        body: opts.body || undefined,
      });
      let body = null;
      const ct = r.headers.get('content-type') || '';
      if (ct.includes('json')) body = await r.json();
      else body = await r.text();
      return { status: r.status, body };
    } catch (e) {
      return { status: 0, body: String(e).slice(0, 300) };
    }
  }, { url, opts, headers });
}

export async function dumpJson(name) {
  const fs = await import('fs');
  fs.writeFileSync(process.cwd() + '/qa_out_' + name + '.json', JSON.stringify(results, null, 2), 'utf8');
}
