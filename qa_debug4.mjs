import { newBrowser, login, BASE } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
await login(page);
await page.goto(BASE + '/settings?tab=academic');
await page.waitForTimeout(4000);
const opts = await page.locator('select[name="template_key"] option').evaluateAll(els => els.map(e => e.value + ':' + e.text).slice(0, 6));
console.log('template opts:', JSON.stringify(opts));
const forms = await page.locator('form').count();
console.log('forms:', forms);
const applyForm = await page.locator('#applyTemplateForm').count();
console.log('applyTemplateForm:', applyForm);
if (applyForm) {
  const btns = await page.locator('#applyTemplateForm button').evaluateAll(els => els.map(e => e.type + '|' + e.textContent.trim()).slice(0, 5));
  console.log('apply buttons:', JSON.stringify(btns));
  const sels = await page.locator('#applyTemplateForm select').count();
  console.log('selects in form:', sels);
}
const stageCards = await page.locator('.stage-card').count();
console.log('stage-cards:', stageCards);
const errs = page._consoleErrors;
console.log('console errors:', JSON.stringify(errs.slice(0, 4)));
await browser.close();
