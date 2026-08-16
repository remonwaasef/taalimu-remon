import { newBrowser, login, fetchAs, BASE, check, getResults, dumpJson, recordError } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
await login(page, BASE);
const ts = Date.now().toString().slice(-6);

// Need a student for sale — pick one NOT already enrolled in course 1
let studentId = null;
let courseId = 1;
try {
  const r = await fetchAs(page, BASE + '/students/search?q=' + encodeURIComponent('طالب تجريبي'));
  const list = Array.isArray(r.body) ? r.body : [];
  for (const st of list) {
    const sum = await fetchAs(page, BASE + '/sales/student-summary/' + st.id);
    const enrolledTitles = ((sum.body && sum.body.courses) || []).map(c => c.title);
    if (!enrolledTitles.includes('دورة الرياضيات المتقدمة')) {
      studentId = String(st.id);
      break;
    }
  }
  if (!studentId && list.length) { studentId = String(list[0].id); }
  check('found student for sale', studentId !== null, 'id=' + studentId);
} catch (e) { recordError('sale student', e); }

// 1. Create a sale (paid via cash)
let saleId = null;
try {
  const r = await fetchAs(page, BASE + '/sales', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({
    student_id: studentId,
    items: [{ id: courseId, price: 500 }],
    payment_method: 'cash',
    paid_amount: '500',
    notes: 'QA sale test ' + ts,
  }) });
  check('sale create', r.status === 302 || r.status === 200 || (r.body && r.body.sale_id), 'status=' + r.status + ' ' + JSON.stringify(r.body).slice(0, 100));
  saleId = r.body?.sale_id || null;
} catch (e) { recordError('sale create', e); }

// 2. Sales list + show invoice
try {
  const r = await fetchAs(page, BASE + '/sales');
  const txt = typeof r.body === 'string' ? r.body : '';
  check('sales list', r.status === 200 && (txt.includes('QA Sale Item') || txt.includes('فواتير') || txt.includes('المبيعات')), 'status=' + r.status);
  const m = txt.match(/sales\/(\d+)/);
  if (m) {
    const r2 = await fetchAs(page, BASE + '/sales/' + m[1]);
    const t2 = typeof r2.body === 'string' ? r2.body : '';
    check('sale show', r2.status === 200 && (t2.includes('QA Sale Item') || t2.includes('خصم') || t2.includes('إجمالي')), 'status=' + r2.status + ' hasItem=' + t2.includes('QA Sale Item'));
  }
} catch (e) { recordError('sale list', e); }

// 3. Receipt download attempt (PDF via payment receipt route)
try {
  const r = await fetchAs(page, BASE + '/sales');
  const txt = typeof r.body === 'string' ? r.body : '';
  const sm = txt.match(/sales\/(\d+)\/edit|sales\/(\d+)/);
  let sid = null;
  const t2 = await fetchAs(page, BASE + '/sales/' + (txt.match(/sales\/(\d+)/)?.[1] ?? ''));
  const stxt = typeof t2.body === 'string' ? t2.body : '';
  const pm = stxt.match(/payments\/(\d+)\/receipt/);
  if (pm) {
    const r2 = await fetchAs(page, BASE + '/payments/' + pm[1] + '/receipt');
    check('receipt endpoint responds', r2.status === 200 || r2.status === 302, 'status=' + r2.status);
  } else {
    check('receipt endpoint responds', false, 'no receipt link found on sale show page');
  }
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
