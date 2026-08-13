import { newBrowser } from './qa_helpers.mjs';
import { createHmac } from 'node:crypto';
import { execSync } from 'node:child_process';
const { browser, page } = await newBrowser();
function totp(secretB32, timestamp = Date.now()) {
  const alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
  let bits = '';
  for (const ch of secretB32) { const idx = alphabet.indexOf(ch); if (idx < 0) continue; bits += idx.toString(2).padStart(5, '0'); }
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
await page.goto('http://localhost:8000/admin/login', { waitUntil: 'domcontentloaded', timeout: 30000 });
await page.waitForTimeout(1500);
await page.evaluate(async () => {
  const tk = document.querySelector('meta[name=csrf-token]')?.content;
  await fetch('/admin/login', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': tk, 'Accept': 'text/html' }, body: new URLSearchParams({ email: 'admin@admin.com', password: 'password', _token: tk }).toString() });
});
await page.waitForTimeout(500);
await page.goto('http://localhost:8000/admin/login/2fa', { waitUntil: 'domcontentloaded', timeout: 30000 });
await page.waitForTimeout(800);
execSync('php "C:/Users/new/AppData/Local/Temp/opencode/delcounters.php"', { stdio: 'inherit' });
const code = totp('JBSWY3DPEHPK3PXP');
console.log('code=' + code + ' at ' + new Date().toISOString());
const r = await page.evaluate(async (otp) => {
  const tk = document.querySelector('meta[name=csrf-token]')?.content;
  const res = await fetch('/admin/login/2fa', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': tk, 'Accept': 'text/html' }, body: new URLSearchParams({ one_time_password: otp, _token: tk }).toString() });
  return { status: res.status, url: res.url, loc: res.headers.get('location'), body: (await res.text()).slice(0, 300) };
}, code);
console.log('POST 2fa: ' + r.status + ' loc=' + r.loc);
if (r.status === 302) {
  const dest = await page.evaluate(async (loc) => { const r2 = await fetch(loc, { headers: { 'Accept': 'text/html' } }); return { status: r2.status, url: r2.url, body: (await r2.text()).slice(0, 200) }; }, r.loc);
  console.log('followed to: ' + dest.status + ' ' + dest.url);
}
console.log('RESULT: ' + ((r.status === 302 || r.status === 200) ? 'SUCCESS' : 'FAIL ' + r.body.replace(/<[^>]+>/g,' ').replace(/\s+/g,' ').slice(0,120)));
await browser.close();
