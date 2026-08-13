import { newBrowser, login, fetchAs, BASE } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
await login(page, BASE);
const cases = [
  ['instructor', '/instructors', { name: 'QA Instructor Ahmed', phone: '01234567890', email: 'qa.instructor.crud@example.com', specialty: 'رياضيات' }],
  ['expense', '/expenses', { title: 'QA Expense', amount: '1250.50', category: 'utilities', date: '2026-08-10' }],
  ['user', '/users', { name: 'QA Staff User', email: 'qa.staff.user@example.com', password: 'Passw0rd!xyz' }],
];
for (const [label, url, data] of cases) {
  const payload = new URLSearchParams(data).toString();
  const r = await fetchAs(page, BASE + url, { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: payload });
  const body = typeof r.body === 'string' ? r.body.slice(0, 300) : JSON.stringify(r.body).slice(0, 300);
  console.log(`--- ${label}: status=${r.status}\n${body}\n`);
}
await browser.close();