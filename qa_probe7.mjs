import { newBrowser, login, BASE } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
const out = [];
const requests = [];
page.on('request', r => { if (r.url().includes('/students/search')) requests.push('REQ: ' + r.url().slice(0, 200)); });
page.on('response', r => { if (r.url().includes('/students/search')) requests.push('RESP: ' + r.status() + ' ' + r.url().slice(0, 200)); });
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
  await page.waitForTimeout(3000);
  const options = await page.locator('.ts-dropdown .option').count();
  out.push('options: ' + options);
  out.push('dropdown html: ' + (await page.locator('.ts-dropdown').first().innerHTML()).slice(0, 400));
  const consoleErrors = page._consoleErrors || [];
  out.push('console errors: ' + consoleErrors.slice(0, 5).join(' || '));
} catch (e) { out.push('ERROR: ' + String(e).slice(0, 500)); }
out.push('requests: ' + requests.join(' || '));
console.log(out.join('\n'));
await browser.close();