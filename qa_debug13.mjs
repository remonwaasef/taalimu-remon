import { newBrowser, login, BASE } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
await login(page);
await page.goto(BASE + '/students');
await page.waitForTimeout(1500);
const row = page.locator('tr', { hasText: 'طالب اختبار QA معدل' }).first();
console.log('rows:', await page.locator('.student-row').count());
if (await row.count()) {
  const html = await row.innerHTML();
  console.log('ROW HTML (truncated):', html.replace(/\s+/g, ' ').slice(0, 3000));
}
await browser.close();