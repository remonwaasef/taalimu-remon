import { newBrowser, login, check, recordError, fetchAs, dumpJson, BASE, getResults } from './qa_helpers.mjs';

const { browser, page } = await newBrowser();

async function getFirstStudent() {
  const r = await fetchAs(page, BASE + '/students/search?q=' + encodeURIComponent('أحمد'));
  if (Array.isArray(r.body) && r.body.length) return r.body[0];
  const r2 = await fetchAs(page, BASE + '/students/search?q=' + encodeURIComponent('طالب'));
  if (Array.isArray(r2.body) && r2.body.length) return r2.body[0];
  const r3 = await fetchAs(page, BASE + '/students/search?q=' + encodeURIComponent('test'));
  return (Array.isArray(r3.body) && r3.body.length) ? r3.body[0] : null;
}

let saleId = null;
let paymentId = null;
let salePrice = 0;
let saleStudentId = null;

// ============ SALES INDEX + CREATE PAGE ============
try {
  await login(page);
  const t0 = Date.now();
  await page.goto(BASE + '/sales');
  await page.waitForTimeout(1500);
  check('sales index loads', page.url().includes('/sales'), 'time=' + (Date.now() - t0) + 'ms');
  const hasTable = await page.locator('table').count();
  check('sales index has table', hasTable > 0, 'tables=' + hasTable);

  await page.goto(BASE + '/sales/create');
  await page.waitForTimeout(1500);
  const courseCards = await page.locator('.course-card').count();
  check('sale create page loads with courses', courseCards > 0, 'courseCards=' + courseCards);
} catch (e) { recordError('sales index/create', e); }

// ============ CREATE SALE VIA API ============
try {
  const courseIds = await page.evaluate(() => {
    const ids = [];
    for (const el of document.querySelectorAll('.course-card')) {
      const onclick = el.getAttribute('onclick') || '';
      const m = onclick.match(/addToCart\((\d+)/);
      if (m) ids.push(m[1]);
    }
    return ids;
  });
  const courseTitles = await page.evaluate(() => {
    const titles = [];
    for (const el of document.querySelectorAll('.course-card h6, .course-card .card-title')) {
      const t = (el.textContent || '').trim();
      if (t) titles.push(t);
    }
    return titles;
  });
  check('found courses for sale', courseIds.length > 0, 'courses=' + JSON.stringify(courseIds) + ' titles=' + JSON.stringify(courseTitles));
  if (!courseIds.length) throw new Error('no courses');

  const students = await fetchAs(page, BASE + '/students/search?q=' + encodeURIComponent('طالب تجريبي'));
  const candidates = Array.isArray(students.body) ? students.body : [];
  let sale = null;
  for (const st of candidates) {
    const sum = await fetchAs(page, BASE + '/sales/student-summary/' + st.id);
    const enrolledTitles = (sum.body?.courses || []).map(c => c.title);
    const freeIdx = courseTitles.findIndex(t => !enrolledTitles.includes(t));
    if (freeIdx >= 0) {
      sale = { student: st, courseId: courseIds[freeIdx], title: courseTitles[freeIdx], price: courseIds[freeIdx] === courseIds[0] ? 500 : 350 };
      break;
    }
  }
  check('found a student+course combo for sale', !!sale, 'candidates=' + candidates.length + ' courses=' + JSON.stringify(courseIds));
  if (!sale) throw new Error('no free student+course combo');
  const created = await fetchAs(page, BASE + '/sales', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      student_id: sale.student.id,
      items: [{ id: parseInt(sale.courseId), price: sale.price }],
      payment_method: 'cash',
      paid_amount: 0,
    }),
  });
  salePrice = sale.price;
  saleId = created.body?.sale_id || null;
  saleStudentId = sale.student.id;
  check('sale created via API', !!saleId, 'saleId=' + saleId + ' student=' + sale.student.id + ' course=' + sale.courseId + ' status=' + created.status);
} catch (e) { recordError('sale create', e); }

// ============ SALE SHOW: total/paid/remaining ============
if (saleId) {
  try {
    await page.goto(BASE + '/sales/' + saleId);
    await page.waitForTimeout(1500);
    const body = await page.locator('body').innerText();
    const hasTotal = new RegExp(String(salePrice)).test(body);
    const hasPaid = /0(\.00)?\s*ج\.م|0\s*جنيه/i.test(body);
    check('sale show page displays total', hasTotal, body.slice(0, 200).replace(/\n/g, ' '));
    check('sale show page displays paid 0', hasPaid, '');
    const studentNameShown = body.includes('طالب') || /أحمد|سارة|محمد|QA/.test(body);
    check('sale show page shows student name', studentNameShown, '');
  } catch (e) { recordError('sale show', e); }

  // ============ ADD PARTIAL PAYMENT ============
  try {
    const r = await fetchAs(page, BASE + '/sales/' + saleId + '/payment', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: 'amount=200&payment_method=cash&notes=QA%20partial%20payment',
    });
    check('add payment 200 accepted', r.status === 200 && r.body?.success !== false, 'status=' + r.status + ' ' + JSON.stringify(r.body).slice(0, 100));
    await page.goto(BASE + '/sales/' + saleId);
    await page.waitForTimeout(1500);
    const body = await page.locator('body').innerText();
    const remaining = salePrice - 200;
    check('show page reflects paid 200 / remaining', /200/.test(body) && new RegExp(String(remaining)).test(body), body.slice(0, 250).replace(/\n/g, ' '));
    const payRow = await page.locator('tr', { hasText: '200' }).count();
    check('payment row visible in sale', payRow > 0, 'rows=' + payRow);
    paymentId = await page.evaluate(() => {
      const link = document.querySelector('a[href*="/payments/"][href*="/receipt"], a[href*="receipt"]');
      const m = link ? link.href.match(/\/payments\/(\d+)\//) : null;
      return m ? m[1] : null;
    });
  } catch (e) { recordError('add payment', e); }

  // ============ STUDENT SUMMARY API ============
  try {
    const student = await getFirstStudent();
    if (student) {
      const r = await fetchAs(page, BASE + '/sales/student-summary/' + student.id);
      check('student summary API returns ledger', r.status === 200 && r.body && typeof r.body === 'object', 'status=' + r.status + ' ' + JSON.stringify(r.body).slice(0, 140));
    } else check('student summary API returns ledger', false, 'no student');
  } catch (e) { recordError('student summary', e); }

  // ============ RECEIPT PDF ============
  try {
    const dlP = page.waitForEvent('download', { timeout: 15000 }).catch(() => null);
    await page.goto(BASE + '/payments/' + paymentId + '/receipt').catch(() => {});
    const download = await dlP;
    check('receipt PDF downloads', !!download, download ? download.suggestedFilename() : 'no download');
  } catch (e) { recordError('receipt', e); }

  // ============ REFUND ============
  try {
    const r = await fetchAs(page, BASE + '/sales/' + saleId + '/refund', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: 'amount=100&refund_method=cash&reason=QA%20test%20refund',
    });
    check('refund 100 accepted', [200, 302].includes(r.status), 'status=' + r.status + ' ' + JSON.stringify(r.body).slice(0, 100));
    const v = await fetchAs(page, BASE + '/sales/student-summary/' + saleStudentId);
    const text = JSON.stringify(v.body || '');
    check('ledger reflects refund', /refund|استرداد/i.test(text), text.slice(0, 140));
  } catch (e) { recordError('refund', e); }

  // ============ CHECKOUT (feature-gated) ============
  try {
    await page.goto(BASE + '/sales/' + saleId + '/checkout');
    await page.waitForTimeout(2000);
    const url = page.url();
    const body = await page.locator('body').innerText();
    const no500 = !/Internal Server Error/.test(body);
    const gated = body.includes('الدفع الإلكتروني غير متاح') || url.includes('paymob') || url.includes('checkout');
    check('checkout either gates or redirects gracefully (no 500)', no500 && (gated || url.includes('/sales/')), url.slice(0, 100));
  } catch (e) { recordError('checkout', e); }
}

// ============ OVERDUE + STATEMENT ============
try {
  await page.goto(BASE + '/sales/overdue');
  await page.waitForTimeout(1500);
  check('overdue page loads', page.url().includes('overdue'), '');
  const student = await getFirstStudent();
  if (student) {
    const dlP2 = page.waitForEvent('download', { timeout: 20000 }).catch(() => null);
    await page.goto(BASE + '/sales/student-statement/' + student.id).catch(() => {});
    const download = await dlP2;
    check('statement PDF downloads', !!download, download ? download.suggestedFilename() : 'no download');
  }
} catch (e) { recordError('overdue/statement', e); }

await dumpJson('finance');
try {
  const { execSync } = await import('child_process');
  execSync('php ' + 'C:/Users/new/AppData/Local/Temp/opencode/db_clean_sales2.php', { stdio: 'ignore' });
  console.log('[CLEAN] QA sales removed from DB');
} catch (e) { console.log('[CLEAN] cleanup failed:', String(e).slice(0, 120)); }
await browser.close();
const results = getResults();
console.log('FINANCE SUITE DONE. Passed: ' + results.checks.filter(c => c.passed).length + '/' + results.checks.length);
