import { newBrowser, login, fetchAs, BASE } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
await login(page, BASE);
let payload = new URLSearchParams({ title: 'QA Course Test', description: 'دورة اختبار QA', price: '1500', status: 'active', duration: '3' });
let r = await fetchAs(page, BASE + '/courses', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: payload.toString() });
console.log('course create:', r.status, typeof r.body === 'string' ? r.body.slice(0, 500) : JSON.stringify(r.body).slice(0, 400));
const r2 = await fetchAs(page, BASE + '/expenses');
console.log('expense list snippet contains QA?', typeof r2.body === 'string' && r2.body.includes('QA Expense'));
await browser.close();