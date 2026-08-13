import { chromium } from '@playwright/test';
import { readdirSync, readFileSync, writeFileSync } from 'node:fs';
import { createHash } from 'node:crypto';
const browser = await chromium.launch();
const ctx = await browser.newContext();
const page = await ctx.newPage();
page.setDefaultTimeout(25000);

const ts = Date.now().toString().slice(-6);
const subdomain = 'qac' + ts;
const email = 'qa.owner' + ts + '@example.com';
const phone = '010' + ts;

try {
  await page.goto('http://localhost:8000/register');
  await page.waitForTimeout(2500);
  await page.fill('input[name="center_name"]', 'QA Center ' + ts);
  await page.fill('input[name="subdomain"]', subdomain);
  await page.locator('button:has-text("التالي")').click();
  await page.waitForTimeout(2000);

  const setVal = (sel, v) => page.evaluate(([s, val]) => {
    const el = document.querySelector(s);
    if (!el) return false;
    el.value = val;
    el.dispatchEvent(new Event('input', { bubbles: true }));
    el.dispatchEvent(new Event('change', { bubbles: true }));
    return true;
  }, [sel, v]);

  await setVal('input[name="name"]', 'QA Owner ' + ts);
  await setVal('input[name="email"]', email);
  await setVal('input[name="phone"]', phone);
  await setVal('input[name="password"]', 'Passw0rd!Qa');
  await setVal('input[name="password_confirmation"]', 'Passw0rd!Qa');
  await page.waitForTimeout(600);

  console.log('phone=' + phone + ' subdomain=' + subdomain + ' email=' + email);
  const otpRes = await page.evaluate(async ({ phone }) => {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const res = await fetch('/api/phone/send-otp', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf },
      body: JSON.stringify({ phone, country_code: '20' }),
    });
    return { status: res.status, body: await res.json().catch(() => ({})) };
  }, { phone });
  console.log('OTP send:', otpRes.status, JSON.stringify(otpRes.body));

  await page.waitForTimeout(1200);

  // Read OTP via PHP (reads cache directly)
  const { execSync } = await import('node:child_process');
  const phpScript = `<?php
require 'D:/new project/antigravty/taalimu.com/taalimu.com/vendor/autoload.php';
$app = require 'D:/new project/antigravty/taalimu.com/taalimu.com/bootstrap/app.php';
$app->make(Illuminate\\Contracts\\Console\\Kernel::class)->bootstrap();
$data = Cache::get('registration_otp_' . md5('${phone}'));
echo $data ? $data['code'] : 'NONE';
`;
  writeFileSync('C:/Users/new/AppData/Local/Temp/opencode/otpget.php', phpScript);
  let code = null;
  try {
    code = execSync('php C:/Users/new/AppData/Local/Temp/opencode/otpget.php', { encoding: 'utf8' }).trim();
  } catch (e) { console.log('php read err:', String(e).slice(0, 200)); }
  console.log('OTP code:', code);

  if (code) {
    const verifyRes = await page.evaluate(async ({ phone, code }) => {
      const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
      const res = await fetch('/api/phone/verify-otp', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf },
        body: JSON.stringify({ phone, otp: code }),
      });
      return { status: res.status, body: await res.json().catch(() => ({})) };
    }, { phone, code });
    console.log('OTP verify:', verifyRes.status, JSON.stringify(verifyRes.body));
  }

  await page.waitForTimeout(1000);
  const body1 = await page.locator('body').innerText();
  console.log('AFTER OTP step:', body1.slice(0, 300).replace(/\n+/g, ' | '));
} catch (e) {
  console.log('ERROR:', String(e).slice(0, 400));
}
await browser.close();