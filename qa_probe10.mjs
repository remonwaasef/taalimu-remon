import { newBrowser, login, fetchAs, BASE } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
await login(page, BASE);
for (const q of ['طالب تجريبي 2', 'طالب تجريبي']) {
  const r = await fetchAs(page, BASE + '/students/search?q=' + encodeURIComponent(q) + '&exclude_course_id=1');
  const n = Array.isArray(r.body) ? r.body.length : 'n/a';
  console.log(`q="${q}" exclude=1 -> status=${r.status} count=${n} sample=${Array.isArray(r.body) && r.body.length ? JSON.stringify(r.body.slice(0,3)) : ''}`);
}
await browser.close();