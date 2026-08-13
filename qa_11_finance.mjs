import { newBrowser, login, fetchAs, BASE, check, getResults, dumpJson, recordError } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
await login(page, BASE);
const ts = Date.now().toString().slice(-6);

// Need a student for sale — list students first
let studentId = null;
try {
  const r = await fetchAs(page, BASE + '/students');
  const txt = typeof r.body === 'string' ? r.body : '';
  const m = txt.match(/students\/(\d+)/);
  studentId = m ? m[1] : '1';
  check('students page for sale', r.status === 200);
} catch (e) { recordError('sale student', e); }

// 1. Create a sale (paid via cash)
try {
  const payload = new URLSearchParams({
    student_id: studentId,
    items: JSON.stringify([{ description: 'QA Sale Item', amount: 500 }]),
    total_amount: '500', paid_amount: '500', payment_method: 'cash', notes: 'QA sale test',
  });
  const r = await fetchAs(page, BASE + '/sales', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: payload.toString() });
  check('sale create', r.status === 302 || r.status === 200, 'status=' + r.status);
} catch (e) { recordError('sale create', e); }

// 2. Sales list + show invoice
try {
  const r = await fetchAs(page, BASE + '/sales');
  const txt = typeof r.body === 'string' ? r.body : '';
  check('sales list', r.status === 200 && (txt.includes('QA Sale Item') || txt.includes('فواتير')), 'status=' + r.status);
  const m = txt.match(/sales\/(\d+)/);
  if (m) {
    const r2 = await fetchAs(page, BASE + '/sales/' + m[1]);
    const t2 = typeof r2.body === 'string' ? r2.body : '';
    check('sale show', r2.status === 200 && (t2.includes('QA Sale Item') || t2.includes('خصم') || t2.includes('إجمالي')), 'status=' + r2.status + ' hasItem=' + t2.includes('QA Sale Item'));
  }
} catch (e) { recordError('sale list', e); }

// 3. Receipt download attempt (PDF or print page)
try {
  const r = await fetchAs(page, BASE + '/sales/receipt/1');
  check('receipt endpoint responds', r.status === 200, 'status=' + r.status);
} catch (e) { recordError('receipt', e); }

// 4. Statements
try {
  const r = await fetchAs(page, BASE + '/students/' + studentId + '/statement');
  check('statement page', r.status === 200, 'status=' + r.status);
} catch (e) { recordError('statement', e); }

await dumpJson('fin');
await browser.close();
const results = getResults();
console.log('FIN DONE. Passed: ' + results.checks.filter(c => c.passed).length + '/' + results.checks.length);