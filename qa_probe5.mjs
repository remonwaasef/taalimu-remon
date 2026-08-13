import { newBrowser, login, fetchAs, BASE } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
await login(page, BASE);
for (const q of ['طالب', 'تجريبي', 'student', '010', 'admin', 'لايوجدxyz']) {
  const r = await fetchAs(page, BASE + '/students/search?q=' + encodeURIComponent(q));
  const n = Array.isArray(r.body) ? r.body.length : 'n/a';
  console.log(`q=${q} -> status=${r.status} count=${n} sample=${Array.isArray(r.body) && r.body.length ? JSON.stringify(r.body[0]) : ''}`);
}
await browser.close();
