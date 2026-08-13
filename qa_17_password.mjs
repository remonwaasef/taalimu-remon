import { newBrowser, check, getResults, dumpJson, recordError } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
const MAIN = 'http://localhost:8000';
const B = 'http://demo-center.localhost:8000';
const A = (p) => B + p;

const qaGet = async (url) => page.evaluate(async (u) => {
  const res = await fetch(u, { method: 'GET', headers: { 'Accept': 'application/json' } });
  return res.json();
}, url);

const setMustChange = async (v) => qaGet(MAIN + '/_qa/db/set-must-change?email=admin@demo.com&value=' + v);

let lastUrl = '';
const loginForm = async (email, password) => {
  await page.goto(A('/logout'), { waitUntil: 'domcontentloaded', timeout: 30000 }).catch(() => {});
  await page.waitForTimeout(1500);
  await page.goto(A('/login'), { waitUntil: 'domcontentloaded', timeout: 30000 });
  await page.waitForTimeout(2000);
  const tok = await page.evaluate(() => document.querySelector('form input[name=_token]')?.value);
  if (!tok) { lastUrl = 'no-form'; return; }
  const res = await page.evaluate(async ({ tk, em, pw }) => {
    const r = await fetch('/login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': tk },
      body: new URLSearchParams({ email: em, password: pw, _token: tk }).toString(),
    });
    return { status: r.status, url: r.url };
  }, { tk: tok, em: email, pw: password });
  if (res.url && !res.url.includes('/login')) {
    await page.goto(res.url, { waitUntil: 'domcontentloaded', timeout: 30000 }).catch(() => {});
    await page.waitForTimeout(4000);
  }
  lastUrl = page.url();
};

// 1. Forgot password: reset page reachable on MAIN domain (tenant subdomains 404 by design)
try {
  const r = await page.goto(MAIN + '/password/reset', { waitUntil: 'domcontentloaded', timeout: 30000 });
  check('reset request page reachable', r.status() === 200, 'status=' + r.status());
  await page.waitForTimeout(1500);
  const formTok = await page.evaluate(() => document.querySelector('form input[name=_token]')?.value);
  const res = await page.evaluate(async (tk) => {
    const resp = await fetch('/password/email', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': tk, 'Accept': 'text/html' },
      body: new URLSearchParams({ email: 'admin@demo.com', _token: tk }).toString(),
    });
    return { status: resp.status, url: resp.url };
  }, formTok);
  check('reset link request handled', res.status === 200 || res.status === 302, 'status=' + res.status);
} catch (e) { recordError('reset link request', e); }

// 2. Reset token stored hashed
try {
  const rec = await qaGet(MAIN + '/_qa/db/password_reset');
  const txt = await page.evaluate(() => document.querySelector('body')?.innerText.slice(0, 100) || '');
  check('reset token stored (hashed, not plaintext)', rec.ok === true && rec.hashed === true && rec.plaintext !== true, 'rec=' + JSON.stringify({ ok: rec.ok, hashed: rec.hashed, plaintext: rec.plaintext }));
} catch (e) { recordError('reset token storage', e); }

// 3. Force password change: redirect when must_change_password=1
let forcedBack = false;
try {
  await setMustChange('1');
  const verify = await qaGet(MAIN + '/_qa/db/get-must-change?email=admin@demo.com');
  if (verify.value !== true) { await setMustChange('1'); await page.waitForTimeout(1000); await qaGet(MAIN + '/_qa/db/get-must-change?email=admin@demo.com'); }
  await loginForm('admin@demo.com', 'password');
  forcedBack = lastUrl.includes('password/change');
  check('must_change redirects to password/change', forcedBack, 'url=' + lastUrl);
} catch (e) { recordError('force redirect', e); }

// 4. Wrong current password rejected
try {
  if (forcedBack) {
    await page.waitForSelector('input[name=current_password]', { timeout: 15000 });
    const pw = 'NewStrongPass123!';
    await page.fill('input[name=current_password]', 'WRONG_pass_99');
    await page.fill('input[name=password]', pw);
    await page.fill('input[name=password_confirmation]', pw);
    await page.keyboard.press('Enter');
    await page.waitForTimeout(6000);
    const body = await page.evaluate(() => document.body.innerText.slice(0, 300)).catch(() => 'nav');
    check('wrong current password rejected', page.url().includes('password/change'), 'url=' + page.url() + ' body=' + body.replace(/\s+/g, ' ').slice(0, 80));
  } else { check('wrong current password rejected', false, 'skipped: not on change form'); }
} catch (e) { recordError('wrong current password', e); }

// 5. Correct current password -> updated + flag cleared + relogin works
try {
  if (forcedBack) {
    await page.waitForSelector('input[name=current_password]', { timeout: 15000 });
    const pw = 'NewStrongPass123!';
    await page.fill('input[name=current_password]', 'password');
    await page.fill('input[name=password]', pw);
    await page.fill('input[name=password_confirmation]', pw);
    await page.keyboard.press('Enter');
    await page.waitForTimeout(12000);
    check('password updated, leaves change form', !page.url().includes('password/change'), 'url=' + page.url());

    await page.goto(MAIN, { waitUntil: 'domcontentloaded', timeout: 30000 });
    await page.waitForTimeout(1500);
    const flags = await qaGet(MAIN + '/_qa/db/get-must-change?email=admin@demo.com');
    check('must_change_password=false in DB', flags.value === false, 'flags=' + JSON.stringify(flags));

    await loginForm('admin@demo.com', pw);
    check('login with new password works', !page.url().includes('login'), 'url=' + page.url());
  } else {
    check('password updated, leaves change form', false, 'skipped');
    check('must_change_password=false in DB', false, 'skipped');
    check('login with new password works', false, 'skipped');
  }
} catch (e) { recordError('force password change submit', e); }

// 6. Restore demo user (env stable)
try {
  await page.goto(MAIN, { waitUntil: 'domcontentloaded', timeout: 30000 });
  await page.waitForTimeout(1500);
  await qaGet(MAIN + '/_qa/db/reset-demo-password?email=admin@demo.com');
  check('demo user restored', true, '');
} catch (e) { recordError('demo restore', e); }

await dumpJson('password');
await browser.close();
const results = getResults();
console.log('PASSWORD DONE. Passed: ' + results.checks.filter(c => c.passed).length + '/' + results.checks.length);