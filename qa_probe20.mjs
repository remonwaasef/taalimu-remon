import { newBrowser, BASE } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
page.setDefaultTimeout(20000);
await page.goto(BASE + '/register');
await page.waitForTimeout(2500);
const html = await page.content();
await import('node:fs').then(fs => fs.writeFileSync('qa_reg_page.html', html));
const forms = await page.locator('form').count();
console.log('forms:', forms);
for (let i = 0; i < forms; i++) {
  const inner = await page.locator('form').nth(i).innerText();
  console.log('--- form ' + i + ' ---');
  console.log(inner.slice(0, 400).replace(/\n+/g, ' | '));
}
await browser.close();