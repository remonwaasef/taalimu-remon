import { newBrowser, login, BASE } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
await login(page, BASE);
const r1 = await page.evaluate(async () => {
  const r = await fetch('/students/search?q=' + encodeURIComponent('طالب تجريبي 2') + '&exclude_course_id=1', { headers: { 'Accept': 'application/json' } });
  return { status: r.status, body: await r.json() };
});
console.log('no X-Requested-With:', JSON.stringify(r1));
const r2 = await page.evaluate(async () => {
  const r = await fetch('/students/search?q=' + encodeURIComponent('طالب تجريبي 2') + '&exclude_course_id=1', { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
  return { status: r.status, body: await r.json() };
});
console.log('with X-Requested-With:', JSON.stringify(r2));
await browser.close();