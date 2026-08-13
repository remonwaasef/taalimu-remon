import { newBrowser, login, BASE } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
const out = [];
try {
  await login(page, BASE);
  await page.goto(BASE + '/courses/1');
  await page.waitForTimeout(2000);
  await page.locator('button[data-bs-target="#enrollStudentModal"]').first().click();
  await page.waitForTimeout(1000);
  await page.locator('.ts-control').first().click();
  await page.waitForTimeout(500);
  const input = page.locator('.ts-dropdown .dropdown-input');
  await input.fill('طالب تجريبي 5');
  await page.waitForTimeout(7000);
  const options = await page.locator('.ts-dropdown .option').count();
  out.push('options after typing: ' + options);
  if (options > 0) {
    const text = await page.locator('.ts-dropdown .option').first().innerText();
    out.push('first option: ' + text);
    await page.locator('.ts-dropdown .option').first().click();
    await page.waitForTimeout(500);
    const sel = await page.locator('#studentSelect').inputValue();
    out.push('selected value: ' + sel);
    await page.click('#enrollStudentModal button[type="submit"]');
    await page.waitForTimeout(3000);
    const body = await page.locator('body').innerText();
    out.push('success: ' + /بنجاح|تم/.test(body));
    out.push('snippet: ' + body.slice(0, 250).replace(/\n+/g, ' | '));
  }
} catch (e) { out.push('ERROR: ' + String(e).slice(0, 500)); }
console.log(out.join('\n'));
await browser.close();
