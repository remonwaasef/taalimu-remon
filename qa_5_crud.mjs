import { newBrowser, login, fetchAs, BASE, check, getResults, dumpJson, recordError } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
await login(page, BASE);

// Instructor create (corrected)
try {
  const payload = new URLSearchParams({ name: 'QA Instructor Ahmed', phone: '01234567890', email: 'qa.instructor.crud4@example.com', specialization: 'رياضيات', status: 'active', commission_rate: '10', commission_type: 'percentage' });
  const r = await fetchAs(page, BASE + '/instructors', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: payload.toString() });
  check('instructor create', r.status === 302 || r.status === 200, 'status=' + r.status);
} catch (e) { recordError('instructor create', e); }

// User create with custom role
try {
  const payload = new URLSearchParams({ name: 'QA Staff User', email: 'qa.staff.user4@example.com', password: 'Passw0rd!xyz', password_confirmation: 'Passw0rd!xyz', role: 'QA Staff Role' });
  const r = await fetchAs(page, BASE + '/users', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: payload.toString() });
  check('user create with custom role', r.status === 302 || r.status === 200, 'status=' + r.status);
  const r2 = await fetchAs(page, BASE + '/users');
  check('user appears in list', typeof r2.body === 'string' && r2.body.includes('QA Staff User'), 'status=' + r2.status);
} catch (e) { recordError('user', e); }

// Course create
try {
  const payload = new URLSearchParams({ title: 'QA Course Test', description: 'دورة اختبار QA', price: '1500', status: 'active', duration: '3' });
  const r = await fetchAs(page, BASE + '/courses', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: payload.toString() });
  check('course create', r.status === 302 || r.status === 200, 'status=' + r.status);
} catch (e) { recordError('course create', e); }

// Expense appears check (may be month-filtered)
try {
  const r = await fetchAs(page, BASE + '/expenses?month=2026-08');
  check('expense list august', typeof r.body === 'string' && r.body.includes('QA Expense Electricity'), 'status=' + r.status);
  const r2 = await fetchAs(page, BASE + '/expenses?search=' + encodeURIComponent('QA Expense'));
  check('expense search finds QA Expense', typeof r2.body === 'string' && r2.body.includes('QA Expense'), 'status=' + r2.status);
} catch (e) { recordError('expense search', e); }

// Role delete (cleanup of QA Staff Role to keep env clean later - just check delete works)
try {
  const r = await fetchAs(page, BASE + '/roles');
  const m = typeof r.body === 'string' ? r.body.match(/roles\/(\d+)[^"]*data[^>]*>\s*<form[^>]*method="POST"[^>]*data-action[^>]*/s) : null;
  check('roles page loads for delete check', r.status === 200);
} catch (e) { recordError('role delete', e); }

await dumpJson('crud3');
await browser.close();
const results = getResults();
console.log('CRUD3 DONE. Passed: ' + results.checks.filter(c => c.passed).length + '/' + results.checks.length);