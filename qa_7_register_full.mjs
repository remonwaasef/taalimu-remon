import { newBrowser, check, getResults, dumpJson, recordError, BASE } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
page.setDefaultTimeout(30000);

const ts = Date.now().toString().slice(-6);
const subdomain = 'qacent' + ts;
const email = 'qa.owner' + ts + '@example.com';

try {
  await page.goto(BASE + '/register');
  await page.waitForTimeout(2500);

  // Step 1: personal data
  await page.fill('input[name="name"]', 'QA Owner ' + ts);
  await page.fill('input[name="email"]', email);
  await page.fill('input[name="phone"]', '01234567890');
  await page.fill('input[name="password"]', 'Passw0rd!xyz');
  await page.fill('input[name="password_confirmation"]', 'Passw0rd!xyz');
  await page.fill('input[name="center_name"]', 'QA Center ' + ts);
  await page.fill('input[name="subdomain"]', subdomain);

  // click next/continue button
  await page.locator('button:has-text("متابعة"), button:has-text("التالي"), button[type="submit"]').first().click();
  await page.waitForTimeout(2500);

  const after1 = await page.locator('body').innerText();
  check('step1 -> step2', after1.includes('الخطوة ٢') || after1.includes('الخطوة 2'), after1.slice(0, 200).replace(/\n+/g, ' | '));

  // Step 2: plan selection - click a free trial / plan radio
  const radios = await page.locator('input[name="plan_selector"], input[type="radio"], input[type="checkbox"]').count();
  console.log('step2 inputs count:', radios);
  // click first plan card
  await page.locator('input[name="plan_selector"]').first().check().catch(async () => {
    await page.locator('label:has-text("مجاني"), label:has-text("Free"), .plan-card, [data-plan]').first().click();
  });
  await page.waitForTimeout(800);

  // Accept terms if present
  const terms = await page.locator('input[type="checkbox"]').count();
  if (terms > 0) {
    for (let i = 0; i < terms; i++) {
      await page.locator('input[type="checkbox"]').nth(i).check().catch(() => {});
    }
  }

  // submit final
  await page.locator('button[type="submit"], button:has-text("إنشاء"), button:has-text("ابدأ")').first().click();
  await page.waitForTimeout(4000);

  const url = page.url();
  const body = await page.locator('body').innerText();
  check('registration completes', /success|مرحبا|لوحة|dashboard|activated|تم/i.test(body) || !url.includes('/register'), url + ' :: ' + body.slice(0, 180).replace(/\n+/g, ' | '));
} catch (e) {
  recordError('registration', e);
  try { await page.screenshot({ path: 'qa_reg_err.png' }); } catch (_) {}
}

console.log('SUBDOMAIN=' + subdomain);
console.log('EMAIL=' + email);
await dumpJson('reg2');
await browser.close();
const results = getResults();
console.log('REG2 DONE. Passed: ' + results.checks.filter(c => c.passed).length + '/' + results.checks.length);