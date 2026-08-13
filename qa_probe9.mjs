import { newBrowser, login, BASE } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
const out = [];
page.on('response', async r => { if (r.url().includes('/students/search')) { const t = Date.now(); const txt = await r.text().catch(() => 'ERR'); out.push(`RESP ${r.status()} in ${Date.now() - t}ms len=${txt.length} :: ${r.url().slice(-80)}`); } });
page.on('pageerror', e => out.push('PAGEERROR: ' + String(e).slice(0, 300)));
page.on('console', m => { if (m.type() === 'error') out.push('CONSOLE: ' + m.text().slice(0, 300)); });
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
  await page.waitForTimeout(10000);
  const options = await page.locator('.ts-dropdown .option').count();
  out.push('FINAL options: ' + options);
  out.push('dropdown html: ' + (await page.locator('.ts-dropdown').first().innerHTML()).slice(0, 300));
} catch (e) { out.push('ERROR: ' + String(e).slice(0, 500)); }
console.log(out.join('\n'));
await browser.close();