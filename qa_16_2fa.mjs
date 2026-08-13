import { newBrowser, check, getResults, dumpJson, recordError } from './qa_helpers.mjs';
import { createHmac } from 'node:crypto';
import { execSync } from 'node:child_process';
const { browser, page } = await newBrowser();
const A = (p) => 'http://localhost:8000/admin' + p;

// Localhost hosts all throttled routes under one sha1('|ip') bucket (empty route domain);
// the route throttle:5,1 plus the global throttle:300,1 share it. Clear counters between steps.
const clearThrottle = () => execSync('php "C:/Users/new/AppData/Local/Temp/opencode/delcounters.php"', { stdio: 'ignore' });

// TOTP computation (RFC 6238, matches PragmaRX defaults: SHA1, 30s window, 6 digits)
function totp(secretB32, timestamp = Date.now()) {
  const alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
  let bits = '';
  for (const ch of secretB32) {
    const idx = alphabet.indexOf(ch);
    if (idx < 0) continue;
    bits += idx.toString(2).padStart(5, '0');
  }
  bits = bits.slice(0, Math.floor(bits.length / 8) * 8);
  const key = Buffer.from(bits.match(/.{8}/g).map(b => parseInt(b, 2)));
  const counter = Math.floor(timestamp / 30000);
  const buf = Buffer.alloc(8);
  buf.writeBigUInt64BE(BigInt(counter));
  const hmac = createHmac('sha1', key).update(buf).digest();
  const offset = hmac[hmac.length - 1] & 0x0f;
  const bin = ((hmac[offset] & 0x7f) << 24) | ((hmac[offset + 1] & 0xff) << 16) | ((hmac[offset + 2] & 0xff) << 8) | (hmac[offset + 3] & 0xff);
  return String(bin % 1000000).padStart(6, '0');
}

// 1. Login without 2FA code -> redirected to 2FA page (since admin now has 2FA enabled)
try {
  await page.goto(A('/login'), { waitUntil: 'domcontentloaded', timeout: 30000 });
  await page.waitForTimeout(1500);
  const r = await page.evaluate(async () => {
    const tk = document.querySelector('meta[name=csrf-token]')?.content;
    const res = await fetch('/admin/login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': tk, 'Accept': 'text/html' },
      body: new URLSearchParams({ email: 'admin@admin.com', password: 'password', _token: tk }).toString(),
    });
    return { status: res.status, url: res.url };
  });
  check('2fa challenge page reached', r.url.includes('admin/login/2fa'), 'url=' + r.url);
} catch (e) { recordError('2fa challenge page', e); }

// 2. Wrong TOTP code rejected
try {
  clearThrottle();
  await page.goto(A('/login/2fa'), { waitUntil: 'domcontentloaded', timeout: 30000 });
  await page.waitForTimeout(1000);
  const r = await page.evaluate(async () => {
    const tk = document.querySelector('meta[name=csrf-token]')?.content;
    const res = await fetch('/admin/login/2fa', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': tk, 'Accept': 'text/html' },
      body: new URLSearchParams({ one_time_password: '000000', _token: tk }).toString(),
    });
    return { status: res.status, url: res.url };
  });
  check('wrong totp rejected', r.url.includes('2fa') || r.url.includes('login'), 'url=' + r.url);
} catch (e) { recordError('wrong totp rejected', e); }

// 3. Correct TOTP code accepted -> dashboard
try {
  clearThrottle();
  const code = totp('JBSWY3DPEHPK3PXP');
  const r = await page.evaluate(async (otp) => {
    const tk = document.querySelector('meta[name=csrf-token]')?.content;
    const res = await fetch('/admin/login/2fa', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': tk, 'Accept': 'text/html' },
      body: new URLSearchParams({ one_time_password: otp, _token: tk }).toString(),
    });
    return { status: res.status, url: res.url };
  }, code);
  check('correct totp accepted', r.url.includes('/admin') && !r.url.includes('login'), 'url=' + r.url + ' code=' + code);
} catch (e) { recordError('correct totp accepted', e); }

await dumpJson('2fa');
await browser.close();
const results = getResults();
console.log('2FA DONE. Passed: ' + results.checks.filter(c => c.passed).length + '/' + results.checks.length);