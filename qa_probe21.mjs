import { chromium } from '@playwright/test';
const browser = await chromium.launch();
const ctx = await browser.newContext();
const page = await ctx.newPage();
page.setDefaultTimeout(20000);
await page.goto('http://localhost:8000/register');
await page.waitForTimeout(3000);
const html = await page.content();
await import('node:fs').then(fs => fs.writeFileSync('qa_reg_page2.html', html));
console.log('url:', page.url());
// find form element(s)
const forms = await page.locator('form').count();
console.log('forms:', forms);
for (let i = 0; i < forms; i++) {
  const cls = await page.locator('form').nth(i).getAttribute('class');
  const action = await page.locator('form').nth(i).getAttribute('action');
  console.log('form', i, 'class=', cls, 'action=', action);
}
// buttons
const btns = await page.locator('button').evaluateAll(els => els.map(e => ({ text: (e.innerText || '').trim().slice(0, 40), type: e.type, cls: (e.className || '').toString().slice(0, 60) })));
console.log('buttons:', JSON.stringify(btns, null, 1).slice(0, 800));
// visible inputs with names
const vis = await page.locator('input:visible').evaluateAll(els => els.map(e => e.name || e.type));
console.log('visible inputs:', JSON.stringify(vis));
// steps
const steps = await page.locator('[data-step], .step, .wizard-step, [x-show]').count();
console.log('step containers:', steps);
await browser.close();