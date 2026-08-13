import { chromium } from '@playwright/test';
import { execSync } from 'node:child_process';
import { readdirSync, readFileSync, writeFileSync } from 'node:fs';
const browser = await chromium.launch();
const ctx = await browser.newContext();
const page = await ctx.newPage();
page.setDefaultTimeout(20000);
const ts = Date.now().toString().slice(-6);
const phone = '011' + ts;
await page.goto('http://localhost:8000/register');
await page.waitForTimeout(2200);
const r = await page.evaluate(async ({ phone }) => {
  const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
  const res = await fetch('/api/phone/send-otp', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf },
    body: JSON.stringify({ phone, country_code: '20' }),
  });
  return { status: res.status, body: await res.json().catch(() => ({})) };
}, { phone });
console.log('send:', r.status, JSON.stringify(r.body));
await page.waitForTimeout(800);
const php = `<?php
require 'D:/new project/antigravty/taalimu.com/taalimu.com/vendor/autoload.php';
$app = require 'D:/new project/antigravty/taalimu.com/taalimu.com/bootstrap/app.php';
$app->make(Illuminate\\Contracts\\Console\\Kernel::class)->bootstrap();
$k = 'taalimu_cache_registration_otp_' . md5('${phone}');
$row = DB::table('cache')->where('key', $k)->first();
if ($row) { $v = unserialize(substr($row->value, strpos($row->value, 'a:'))); echo $v['code']; } else { echo 'NONE'; }
`;
writeFileSync('C:/Users/new/AppData/Local/Temp/opencode/otpget3.php', php);
const out = execSync('php C:/Users/new/AppData/Local/Temp/opencode/otpget3.php', { encoding: 'utf8' });
console.log('OTP code:', out.trim());
await browser.close();