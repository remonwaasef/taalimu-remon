import { newBrowser, login, fetchAs, BASE } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
page.setDefaultTimeout(30000);
page.on('response', (r) => {
  const u = r.url();
  if (u.includes('/students')) console.log('RESP', r.status(), r.request().method(), u.replace('http://demo-center.localhost:8000', ''));
});
await login(page);
const r = await fetchAs(page, BASE + '/students/search?q=' + encodeURIComponent('طالب اختبار QA'));
const s = Array.isArray(r.body) && r.body.length ? r.body[0] : null;
console.log('student:', JSON.stringify(s));
if (!s) { await browser.close(); process.exit(0); }
await page.goto(BASE + '/students/' + s.id + '/edit');
await page.waitForTimeout(1200);
const newName = 'طالب اختبار QA معدل';
await page.fill('input[name="name"]', newName);
const form = page.locator('form', { has: page.locator('input[name="name"]') });
console.log('target forms:', await form.count());
const action = await form.first().getAttribute('action');
console.log('form action:', action);
const method = await form.first().getAttribute('method');
console.log('form method:', method);
const hasToken = await form.first().locator('input[name="_token"]').count();
console.log('has _token input:', hasToken);
await form.first().locator('button[type="submit"]').first().click().catch((e) => console.log('click err', e));
await page.waitForTimeout(4000);
console.log('final url:', page.url());
await browser.close();