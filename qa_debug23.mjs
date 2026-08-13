import { newBrowser, login, BASE } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
page.setDefaultTimeout(45000);
await login(page);
for (const path of ['/courses/1/edit', '/courses/1', '/students/1', '/instructors/1/edit']) {
  const r = await page.evaluate(async (p) => {
    try {
      const resp = await fetch(p, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
      const text = await resp.text();
      return { status: resp.status, head: text.slice(0, 200) };
    } catch (e) { return { status: 0, head: String(e).slice(0, 150) }; }
  }, path);
  console.log(path, '->', r.status, '|', r.head.replace(/\s+/g, ' ').slice(0, 150));
}
await browser.close();