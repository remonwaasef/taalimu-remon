import { newBrowser, login, BASE } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
const out = [];
page.on('request', r => { if (r.url().includes('/students/search')) out.push('REQ: ' + decodeURIComponent(r.url()).slice(-100)); });
page.on('response', async r => { if (r.url().includes('/students/search')) { out.push('RESP ' + r.status() + ': ' + (await r.text().catch(()=>'ERR')).slice(0, 300)); } });
page.on('pageerror', e => out.push('PAGEERROR: ' + String(e).slice(0, 300)));
try {
  await login(page, BASE);
  await page.goto(BASE + '/courses/1');
  await page.waitForTimeout(2000);
  await page.locator('button[data-bs-target="#enrollStudentModal"]').first().click();
  await page.waitForTimeout(800);
  await page.locator('.ts-control').first().click();
  await page.waitForTimeout(800);
  const input = page.locator('.ts-dropdown .dropdown-input');
  await input.fill('طالب تجريبي 2');
  await page.waitForTimeout(8000);
  const options = await page.locator('.ts-dropdown .option').count();
  out.push('FINAL options: ' + options);
  if (options > 0) out.push('first: ' + await page.locator('.ts-dropdown .option').first().innerText());
} catch (e) { out.push('ERROR: ' + String(e).slice(0, 500)); }
console.log(out.join('\n'));
await browser.close();