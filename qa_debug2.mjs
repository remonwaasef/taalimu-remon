import { newBrowser, login, BASE } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
await login(page);
await page.goto(BASE + '/students/create');
await page.waitForTimeout(2000);
const body = await page.locator('body').innerText();
console.log('URL:', page.url());
console.log('BODY HEAD:', body.slice(0, 600).replace(/\n/g, ' | '));
const selects = await page.locator('select').count();
console.log('selects:', selects);
if (selects) {
  const ids = await page.locator('select').evaluateAll(els => els.map(e => e.id));
  console.log('select ids:', JSON.stringify(ids));
}
const checkboxes = await page.locator('.course-checkbox-item').count();
console.log('course checkboxes:', checkboxes);
const errors = page._consoleErrors;
console.log('CONSOLE ERRORS:', JSON.stringify(errors.slice(0, 5)));
await browser.close();
