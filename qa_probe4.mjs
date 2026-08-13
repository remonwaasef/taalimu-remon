import { newBrowser, login, fetchAs, BASE } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
await login(page, BASE);
const r = await fetchAs(page, BASE + '/students/search?q=&exclude_course_id=1');
console.log('status:', r.status);
console.log('body:', JSON.stringify(r.body).slice(0, 800));
const r2 = await fetchAs(page, BASE + '/students/search?q=' + encodeURIComponent('أحمد'));
console.log('q=أحمد status:', r2.status, 'count:', Array.isArray(r2.body) ? r2.body.length : 'n/a');
await browser.close();
