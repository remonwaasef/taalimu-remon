import { newBrowser, login, fetchAs, BASE } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
await login(page, BASE);
for (const p of ['/analytics/students', '/billing', '/billing/create']) {
  const r = await fetchAs(page, BASE + p);
  console.log(p, '-> status=' + r.status);
}
await browser.close();