import { newBrowser, login, fetchAs, BASE, check, getResults, dumpJson, recordError } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
await login(page, BASE);
const ts = Date.now().toString().slice(-6);
const email = 'qa.crud.' + ts + '@example.com';
const staffEmail = 'qa.staff.' + ts + '@example.com';

// Instructor create
try {
  const payload = new URLSearchParams({ name: 'QA Instructor ' + ts, phone: '012345' + ts.slice(0, 6), email, specialization: 'رياضيات', status: 'active', commission_rate: '10', commission_type: 'percentage' });
  const r = await fetchAs(page, BASE + '/instructors', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: payload.toString() });
  check('instructor create', r.status === 302 || r.status === 200, 'status=' + r.status);
} catch (e) { recordError('instructor create', e); }

// User create with custom role (QA Staff Role exists in tenant 1)
try {
  const payload = new URLSearchParams({ name: 'QA Staff ' + ts, email: staffEmail, password: 'Passw0rd!xyz', password_confirmation: 'Passw0rd!xyz', role: 'QA Staff Role' });
  const r = await fetchAs(page, BASE + '/users', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: payload.toString() });
  check('user create with custom role', r.status === 302 || r.status === 200, 'status=' + r.status);
  const r2 = await fetchAs(page, BASE + '/users');
  check('user appears in list', typeof r2.body === 'string' && r2.body.includes('QA Staff ' + ts), 'status=' + r2.status);
} catch (e) { recordError('user', e); }

// Course create (status must be draft/published; needs sessions_count)
try {
  const payload = new URLSearchParams({ title: 'QA Course ' + ts, description: 'دورة اختبار QA', price: '1500', status: 'draft', sessions_count: '0' });
  const r = await fetchAs(page, BASE + '/courses', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: payload.toString() });
  check('course create', r.status === 302 || r.status === 200, 'status=' + r.status);
} catch (e) { recordError('course create', e); }

// Expense create + appears in list (table renders category/amount/date, not description)
try {
  const r = await fetchAs(page, BASE + '/expenses', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ category: 'QA Cat ' + ts, amount: 200, date: '2026-08-10', payment_method: 'cash', description: 'QA Expense ' + ts }) });
  check('expense create', r.status === 302 || r.status === 200, 'status=' + r.status);
  const r2 = await fetchAs(page, BASE + '/expenses');
  const txt = typeof r2.body === 'string' ? r2.body : '';
  check('expense appears in list', txt.includes('QA Cat ' + ts) && txt.includes('200'), 'status=' + r2.status);
  const r3 = await fetchAs(page, BASE + '/expenses?search=' + encodeURIComponent('QA Cat ' + ts));
  check('expense search finds QA expense', typeof r3.body === 'string' && r3.body.includes('QA Cat ' + ts), 'status=' + r3.status);
} catch (e) { recordError('expense', e); }

await dumpJson('crud');
await browser.close();
const results = getResults();
console.log('CRUD DONE. Passed: ' + results.checks.filter(c => c.passed).length + '/' + results.checks.length);
