import { newBrowser, login, check, recordError, dumpJson, BASE } from './qa_helpers.mjs';
import { execSync } from 'node:child_process';

const { browser, ctx, page } = await newBrowser();
const notes = 'QA booking test ' + Date.now().toString().slice(-5);

const latestBookingId = () => {
  const out = execSync('php "C:\\Users\\new\\AppData\\Local\\Temp\\opencode\\qa21_cleanup.php" latest-booking', { encoding: 'utf8' }).trim();
  return out && out !== 'null' ? out : null;
};

try {
  execSync('php "C:\\Users\\new\\AppData\\Local\\Temp\\opencode\\qa21_cleanup.php" clean-bookings', { encoding: 'utf8' });
  await login(page);
  await page.goto(BASE + '/students/1', { waitUntil: 'domcontentloaded' });
  await page.waitForTimeout(1500);

  const opts = await page.locator('#addBookingModal select[name="schedule_id"] option').count();
  check('booking modal has schedule options', opts > 1, 'options=' + opts);

  if (opts > 1) {
    const stored = await page.evaluate(() => {
      const sel = document.querySelector('#addBookingModal select[name="schedule_id"]');
      sel.selectedIndex = 1;
      return sel.options[1].value;
    });
    const res = await page.evaluate(async (notes) => {
      const form = document.querySelector('#addBookingModal form');
      const token = form.querySelector('input[name="_token"]').value;
      const studentId = form.querySelector('input[name="student_id"]').value;
      const sel = form.querySelector('select[name="schedule_id"]');
      const fd = new FormData();
      fd.append('student_id', studentId);
      fd.append('schedule_id', sel.value);
      fd.append('notes', notes);
      const r = await fetch('/bookings', { method: 'POST', headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }, body: fd });
      return r.status;
    }, notes);
    await page.waitForTimeout(1500);
    const bid = latestBookingId();
    check('booking created in DB (capacity bug fixed)', bid !== null && res === 200, 'http=' + res + ' id=' + bid);

    await page.goto(BASE + '/students/1', { waitUntil: 'domcontentloaded' });
    await page.waitForTimeout(1500);
    const rows = await page.locator('#pills-bookings tbody tr').count();
    const tabTxt = await page.locator('#pills-bookings').innerText().catch(() => '');
    check('booking visible in bookings tab', rows > 0 && tabTxt.includes('confirmed'), 'rows=' + rows);

    if (bid) {
      const getToken = () => page.evaluate(() => document.querySelector('#addBookingModal form input[name="_token"]')?.value || '');
      const token = await getToken();
      const patch = (status) => page.evaluate(async ({ id, status, token }) => {
        const r = await fetch('/bookings/' + id + '/status', { method: 'POST', headers: { 'X-CSRF-TOKEN': token, 'Content-Type': 'application/json', 'Accept': 'application/json' }, body: JSON.stringify({ _method: 'PATCH', status }) });
        return r.status;
      }, { id: bid, status, token });

      const s1 = await patch('cancelled');
      const s2 = await patch('confirmed');
      check('booking.updateStatus (cancel+confirm) accepted', s1 === 200 && s2 === 200, JSON.stringify({ s1, s2 }));

      const d3 = await page.evaluate(async ({ id, token }) => {
        const r = await fetch('/bookings/' + id, { method: 'POST', headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }, body: new URLSearchParams({ _method: 'DELETE' }) });
        return r.status;
      }, { id: bid, token });
      await page.waitForTimeout(1200);
      const afterDel = latestBookingId();
      check('booking.destroy removes row', d3 === 200 && afterDel !== bid, JSON.stringify({ d3, afterDel }));
    }
  }
} catch (e) {
  recordError('qa24_bookings', e);
}

console.log('\n--- CONSOLE ERRORS (first 5) ---');
console.log((page._consoleErrors || []).slice(0, 5).join('\n') || 'none');
await dumpJson('24_bookings');
await browser.close();