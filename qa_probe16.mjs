import { newBrowser, login, fetchAs, BASE } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
await login(page, BASE, { email: 'instructor1@demo.com', password: 'password' });
const r = await fetchAs(page, BASE + '/instructor/students-list');
console.log('/instructor/students-list -> status=' + r.status);
await browser.close();