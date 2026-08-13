import { chromium } from '@playwright/test';
import { execSync } from 'node:child_process';
import { writeFileSync } from 'node:fs';
const browser = await chromium.launch();
const ctx = await browser.newContext();
const page = await ctx.newPage();
page.setDefaultTimeout(25000);

const ts = Date.now().toString().slice(-6);
const subdomain = 'qac' + ts;
const email = 'qa.owner' + ts + '@example.com';
const phone = '010' + ts;

function readOtp(phone) {
  const php = `<?php
require 'D:/new project/antigravty/taalimu.com/taalimu.com/vendor/autoload.php';
$app = require 'D:/new project/antigravty/taalimu.com/taalimu.com/bootstrap/app.php';
$app->make(Illuminate\\Contracts\\Console\\Kernel::class)->bootstrap();
$k = 'taalimu_cache_registration_otp_' . md5('${phone}');
$row = DB::table('cache')->where('key', $k)->first();
if ($row) { $v = unserialize(substr($row->value, strpos($row->value, 'a:'))); echo $v['code']; } else { echo 'NONE'; }
`;
  writeFileSync('C:/Users/new/AppData/Local/Temp/opencode/otprd.php', php);
  return execSync('php C:/Users/new/AppData/Local/Temp/opencode/otprd.php', { encoding: 'utf8' }).trim();
}

const setVal = (sel, v) => page.evaluate(([s, val]) => {
  const el = document.querySelector(s);
  if (!el) return false;
  el.value = val;
  el.dispatchEvent(new Event('input', { bubbles: true }));
  el.dispatchEvent(new Event('change', { bubbles: true }));
  return true;
}, [sel, v]);

try {
  await page.goto('http://localhost:8000/register');
  await page.waitForTimeout(2500);
  await setVal('input[name="center_name"]', 'QA Center ' + ts);
  await setVal('input[name="subdomain"]', subdomain);
  await page.waitForTimeout(600);
  await page.locator('button:has-text("التالي")').click();
  await page.waitForTimeout(1800);

  await setVal('input[name="name"]', 'QA Owner ' + ts);
  await setVal('input[name="email"]', email);
  await setVal('input[name="phone"]', phone);
  await setVal('input[name="password"]', 'Passw0rd!Qa');
  await setVal('input[name="password_confirmation"]', 'Passw0rd!Qa');
  await page.waitForTimeout(500);

  // Send OTP via UI button
  await page.locator('button:has-text("إرسال كود")').first().click().catch(async () => {
    await page.evaluate(async ({ phone }) => {
      const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
      await fetch('/api/phone/send-otp', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf },
        body: JSON.stringify({ phone, country_code: '20' }),
      });
    }, { phone });
  });
  await page.waitForTimeout(2500);

  const code = readOtp(phone);
  console.log('OTP read:', code);

  if (code !== 'NONE') {
    await setVal('input[maxlength="6"], input[inputmode="numeric"]', code);
    await page.waitForTimeout(2500);
    const body = await page.locator('body').innerText();
    console.log('after verify contains Verified?', body.includes('تم التحقق'), '| otep errors?', body.slice(0, 400).replace(/\n+/g, ' | '));
  }

  // Submit the form (single form wraps all steps)
  const formOk = await page.evaluate(() => {
    const form = document.querySelector('form[action*="register"]');
    if (!form) return 'NO FORM';
    form.requestSubmit();
    return 'SUB';
  });
  console.log('form submit:', formOk);
  await page.waitForTimeout(5000);
  console.log('final URL:', page.url());
  const finalBody = await page.locator('body').innerText();
  console.log('final page:', finalBody.slice(0, 350).replace(/\n+/g, ' | '));
} catch (e) {
  console.log('ERROR:', String(e).slice(0, 500));
}
console.log('SUBDOMAIN=' + subdomain + ' PHONE=' + phone);
await browser.close();