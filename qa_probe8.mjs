import { newBrowser, login, fetchAs, BASE } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
await login(page, BASE);
for (const q of ['طالب', 'طالب تجريبي 5', 'تجريبي 5']) {
  const t0 = Date.now();
  const r = await fetchAs(page, BASE + '/students/search?q=' + encodeURIComponent(q));
  const ms = Date.now() - t0;
  const n = Array.isArray(r.body) ? r.body.length : 'n/a';
  console.log(`q="${q}" -> ${ms}ms status=${r.status} count=${n}`);
}
await browser.close();