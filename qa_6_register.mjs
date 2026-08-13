import { newBrowser, BASE, check, getResults, dumpJson, recordError } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
page.setDefaultTimeout(30000);

// Register page loads
try {
  await page.goto('http://localhost:8000/register');
  await page.waitForTimeout(2500);
  const body = await page.locator('body').innerText();
  check('register page loads', body.includes('ابدأ التجربة المجانية') || body.includes('تسجيل'), page.url());
} catch (e) { recordError('register page', e); }

// Try submitting empty form (validation)
try {
  await page.goto('http://localhost:8000/register');
  await page.waitForTimeout(2000);
  await page.click('button[type="submit"]').catch(async () => {
    const btn = page.locator('button:has-text("التالي"), button:has-text("متابعة"), button[type="submit"]').first();
    await btn.click();
  });
  await page.waitForTimeout(1500);
  const body = await page.locator('body').innerText();
  const hasErrors = /مطلوب|required|error/.test(body);
  check('empty register blocked', hasErrors, body.slice(0, 200).replace(/\n+/g, ' | '));
} catch (e) { recordError('register empty', e); }

// Fill step 1
try {
  await page.goto('http://localhost:8000/register');
  await page.waitForTimeout(2000);
  const inputs = await page.locator('input').count();
  const names = await page.locator('input').evaluateAll(els => els.map(e => e.name || e.placeholder || e.type));
  console.log('register inputs:', JSON.stringify(names));
  const bodyText = await page.locator('body').innerText();
  check('register page shows step 1', bodyText.includes('الخطوة'), bodyText.slice(0, 150).replace(/\n+/g, ' | '));
} catch (e) { recordError('register fields', e); }

await dumpJson('reg');
await browser.close();
const results = getResults();
console.log('REG DONE. Passed: ' + results.checks.filter(c => c.passed).length + '/' + results.checks.length);