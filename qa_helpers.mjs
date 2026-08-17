import { chromium } from '@playwright/test';

export const BASE = process.env.QA_BASE ?? 'http://demo-center.localhost:8000';
export const ADMIN = { email: process.env.QA_ADMIN_EMAIL ?? 'admin@demo.com', password: process.env.QA_ADMIN_PASSWORD ?? 'password' };

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
  const submitBtn = page.locator('form[action*="login"] button[type="submit"], div.auth-form button[type="submit"], form button[type="submit"]').first();
  await Promise.all([
    page.waitForNavigation({ waitUntil: 'commit', timeout: 30000 }).catch(() => {}),
    submitBtn.click().catch(() => page.keyboard.press('Enter')),
  ]);
  await page.waitForTimeout(1200);
}

export async function fetchAs(page, url, opts = {}) {  const cookies = await page.context().cookies();
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

export async function adminLogin(page) {
  const A = (p) => 'http://localhost:8000/admin' + p;
  await page.goto(A('/login'), { waitUntil: 'domcontentloaded', timeout: 30000 });
  await page.waitForTimeout(1500);
  const r = await page.evaluate(async () => {
    const tk = document.querySelector('meta[name=csrf-token]')?.content;
    const res = await fetch('/admin/login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': tk, 'Accept': 'text/html' },
      body: new URLSearchParams({ email: process.env.QA_ADMIN_EMAIL ?? 'admin@admin.com', password: process.env.QA_ADMIN_PASSWORD ?? 'password', _token: tk }).toString(),
    });
    return { url: res.url, status: res.status };
  });
  await page.waitForTimeout(1000);
  if (r.url.includes('/admin/login/2fa')) {
    await page.goto(A('/login/2fa'), { waitUntil: 'domcontentloaded', timeout: 30000 });
    await page.waitForTimeout(1000);
    await page.evaluate(async () => {
      const tk = document.querySelector('meta[name=csrf-token]')?.content;
      const secret = process.env.QA_2FA_SECRET ?? 'JBSWY3DPEHPK3PXP';
      const alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
      let bits = '';
      for (const ch of secret) {
        const idx = alphabet.indexOf(ch);
        if (idx < 0) continue;
        bits += idx.toString(2).padStart(5, '0');
      }
      bits = bits.slice(0, Math.floor(bits.length / 8) * 8);
      const key = Uint8Array.from(bits.match(/.{8}/g).map(b => parseInt(b, 2)));
      const counter = Math.floor(Date.now() / 30000);
      const buf = new ArrayBuffer(8);
      const dv = new DataView(buf);
      dv.setBigUint64(0, BigInt(counter));
      const cryptoObj = window.crypto.subtle;
      const sig = await cryptoObj.sign({ name: 'HMAC' }, await cryptoObj.importKey('raw', key, { name: 'HMAC', hash: 'SHA-1' }, false, ['sign']), new Uint8Array(buf));
      const hmac = new Uint8Array(sig);
      const offset = hmac[hmac.length - 1] & 0x0f;
      const bin = ((hmac[offset] & 0x7f) << 24) | ((hmac[offset + 1] & 0xff) << 16) | ((hmac[offset + 2] & 0xff) << 8) | (hmac[offset + 3] & 0xff);
      const otp = String(bin % 1000000).padStart(6, '0');
      await fetch('/admin/login/2fa', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': tk, 'Accept': 'text/html' },
        body: new URLSearchParams({ one_time_password: otp, _token: tk }).toString(),
      });
    });
    await page.waitForTimeout(1200);
  }
}
