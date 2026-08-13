import { newBrowser, login, fetchAs, BASE, check, getResults, dumpJson, recordError } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
await login(page, BASE);

// 0. Create a fresh student (no enrollments) to sell to
let newStudentId = null;
try {
  const payload = new URLSearchParams();
  payload.append('name', 'طالب مالي QA');
  payload.append('phone', '011' + String(Date.now()).slice(-8));
  payload.append('email', 'fin.qa' + Date.now() % 100000 + '@example.com');
  payload.append('status', 'active');
  payload.append('gender', 'male');
  payload.append('grade_id', '1');
  payload.append('course_ids[]', '2');
  const r = await fetchAs(page, BASE + '/students', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: payload.toString() });
  check('create fresh student', r.status === 302 || r.status === 200 || r.status === 201, 'status=' + r.status);
  const txt = typeof r.body === 'string' ? r.body : '';
  const m = txt.match(/students\/(\d+)/g);
  if (m && m.length) newStudentId = m[m.length - 1].split('/')[1];
  if (!newStudentId) {
    const rl = await fetchAs(page, BASE + '/students');
    const t2 = typeof rl.body === 'string' ? rl.body : '';
    const m2 = t2.match(/students\/(\d+)/g);
    if (m2 && m2.length) newStudentId = m2[m2.length - 1].split('/')[1];
  }
  if (!newStudentId) { const j = JSON.parse(txt || '{}'); newStudentId = j.id || j.student?.id || null; }
} catch (e) { recordError('create student', e); }

// 1. Create sale with correct payload (items = course ids)
try {
  const payload = new URLSearchParams();
  payload.append('student_id', newStudentId || '1');
  payload.append('items[0][id]', '1');
  payload.append('items[0][price]', '500');
  payload.append('payment_method', 'cash');
  payload.append('paid_amount', '500');
  const r = await fetchAs(page, BASE + '/sales', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: payload.toString() });
  check('sale create (course items)', r.status === 302 || r.status === 200 || r.status === 201, 'status=' + r.status + ' :: ' + (typeof r.body === 'string' ? r.body.slice(0, 150) : JSON.stringify(r.body).slice(0, 150)));
} catch (e) { recordError('sale create', e); }

// 2. Sales list + show
let saleId = null;
try {
  const r = await fetchAs(page, BASE + '/sales');
  const txt = typeof r.body === 'string' ? r.body : '';
  const m = txt.match(/sales\/(\d+)/g);
  if (m && m.length) {
    saleId = m[m.length - 1].split('/')[1];
  }
  check('sales list', r.status === 200, 'status=' + r.status);
} catch (e) { recordError('sales list', e); }

// 3. Receipt route (check actual route name)
try {
  const routes = [
    BASE + '/sales/receipt/1',
    BASE + '/receipts/' + (saleId || 1),
    BASE + '/payments/6/receipt',
  ];
  let found = false;
  for (const u of routes) {
    const r = await fetchAs(page, u);
    if (r.status === 200) { found = true; check('receipt endpoint ' + u.replace(BASE, ''), true, 'status=200'); break; }
  }
  if (!found) check('receipt endpoint (any)', false, 'all routes non-200');
} catch (e) { recordError('receipt', e); }

// 4. Statement for student 1
try {
  const r = await fetchAs(page, BASE + '/students/1/statement');
  check('statement page', r.status === 200, 'status=' + r.status);
} catch (e) { recordError('statement', e); }

// 5. Refund flow on latest sale
try {
  if (saleId) {
    const payload = new URLSearchParams({ amount: '100', reason: 'QA refund test', refund_method: 'cash', unenroll_student: '1' });
    const r = await fetchAs(page, BASE + '/sales/' + saleId + '/refund', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: payload.toString() });
    check('sale refund', r.status === 302 || r.status === 200, 'status=' + r.status);
  } else { check('sale refund (no sale found)', false, 'no saleId'); }
} catch (e) { recordError('refund', e); }

await dumpJson('fin2');
await browser.close();
const results = getResults();
console.log('FIN2 DONE. Passed: ' + results.checks.filter(c => c.passed).length + '/' + results.checks.length);