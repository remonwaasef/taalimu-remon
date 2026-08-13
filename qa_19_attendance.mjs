import { newBrowser } from './qa_helpers.mjs';
import { execSync } from 'node:child_process';

const BASE = 'D:\\new project\\antigravty\\taalimu.com\\taalimu.com';
const php = (script) => execSync('php "' + BASE + '\\qa_db_helpers.php" ' + script, { encoding: 'utf8' }).trim();
const qaDb = async (script) => JSON.parse(php(script));
const B = process.env.QA_BASE_URL || 'http://qa-center-194003.localhost:8000';
const A = (p) => B + p;
const { browser, page } = await newBrowser();

let passed = 0, failed = 0;
const results = [];
const check = (name, ok, info) => {
  results.push({ name, ok, info });
  console.log(`[${ok ? 'PASS' : 'FAIL'}] ${name} :: ${info ?? ''}`);
  ok ? passed++ : failed++;
};
const recordError = (label, e) => {
  results.push({ name: label, ok: false, info: e.message.slice(0, 200) });
  console.log(`[ERROR] ${label} :: ${e.message.slice(0, 200)}`);
  failed++;
};
const waitNav = async (ms) => { await page.waitForTimeout(ms || 4000); };
const login = async (email, password) => {
  await page.goto(A('/login'), { waitUntil: 'domcontentloaded', timeout: 30000 }).catch(() => {});
  await page.waitForTimeout(2500);
  const tok = await page.evaluate(() => document.querySelector('form input[name=_token]')?.value);
  if (!tok) { await page.fill('input[name=email]', email).catch(() => {}); await page.fill('input[name=password]', password).catch(() => {}); await page.keyboard.press('Enter').catch(() => {}); }
  else {
    const res = await page.evaluate(async ({ tk, em, pw }) => {
      const r = await fetch('/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': tk },
        body: new URLSearchParams({ email: em, password: pw, _token: tk }).toString(),
        redirect: 'manual',
      });
      return { status: r.status, loc: r.headers.get('location') };
    }, { tk: tok, em: email, pw: password });
    const target = (res.loc && !res.loc.includes('/login')) ? res.loc : '/';
    await page.goto(A(target), { waitUntil: 'domcontentloaded', timeout: 30000 }).catch(() => {});
  }
  for (let i = 0; i < 25; i++) { await page.waitForTimeout(1000); if (!page.url().includes('/login')) break; }
  return page.url();
};

let scheduleId = null;

try { php('cleanupt3att'); } catch (e) { console.log('  [dbg] precleanup: ' + e.message.slice(0, 80)); }

try {
  const url = await login('qa.owner194003@example.com', 'password');
  check('admin logged in', !url.includes('/login'), 'url=' + url);
} catch (e) { recordError('admin login', e); }

// schedules index
try {
  const r = await page.goto(A('/schedules'), { waitUntil: 'domcontentloaded', timeout: 30000 });
  await page.waitForTimeout(2500);
  const txt = await page.evaluate(() => document.body.innerText.length);
  check('schedules index accessible', r.status() === 200 && txt > 100, 'status=' + r.status() + ' len=' + txt);
} catch (e) { recordError('schedules index', e); }

// schedule create flow
let createdSchedId = null;
try {
  const fx = await qaDb('make_t3_att_schedule');
  createdSchedId = fx.schedule;
  check('schedule fixture created in DB', !!createdSchedId, 'id=' + createdSchedId);
} catch (e) { recordError('schedule fixture', e); }

// mark attendance via UI: present / late / absent
try {
  if (createdSchedId) {
    scheduleId = createdSchedId;
    const fx2 = await qaDb('ensure_t3_att_fixtures');
    const r = await page.goto(A('/attendance/schedule/' + scheduleId), { waitUntil: 'domcontentloaded', timeout: 30000 });
    await page.waitForTimeout(2500);
    check('attendance show page 200', r.status() === 200, 'status=' + r.status());
    const nStudents = await page.evaluate(() => document.querySelectorAll('tbody tr').length);
    check('enrolled students rendered', nStudents >= 1, 'rows=' + nStudents);
    await page.goto(A('/attendance'), { waitUntil: 'domcontentloaded', timeout: 30000 });
    await page.waitForTimeout(2500);
    const iTxt = await page.evaluate(() => document.body.innerText.length);
    check('attendance index accessible', iTxt > 100, 'len=' + iTxt);

    // mark first student present via the show page forms
    const studentId = 24;
    const presentForm = 'form[action$="/attendance"]:has(input[name=student_id][value="' + studentId + '"])';
    await page.goto(A('/attendance/schedule/' + scheduleId), { waitUntil: 'domcontentloaded', timeout: 30000 });
    await page.waitForTimeout(2500);
    const presentBtn = await page.$(presentForm + ' button[type=submit]').catch(() => null);
    if (presentBtn) {
      await page.click(presentForm + ' button[type=submit]');
      await waitNav(4000);
      const atts = await qaDb('attsched' + scheduleId);
      const row = atts.find(a => a.student_id === studentId);
      check('present marked in DB', row && row.status === 'present', 'status=' + (row && row.status));
    } else { check('present marked in DB', false, 'no form'); }
  } else { check('attendance mark skipped', false, 'no schedule'); }
} catch (e) { recordError('attendance mark present', e); }

// mark late via modal
try {
  if (scheduleId) {
    const studentId = 25;
    await page.goto(A('/attendance/schedule/' + scheduleId), { waitUntil: 'domcontentloaded', timeout: 30000 });
    await page.waitForTimeout(2500);
    await page.click('button[onclick*="openLateModal(25,"]').catch(() => {
      return page.evaluate(() => {
        const btn = [...document.querySelectorAll('button')].find(b => (b.getAttribute('onclick') || '').includes('openLateModal(25,'));
        if (btn) btn.click();
        return !!btn;
      });
    }).catch(() => {});
    await page.waitForTimeout(1200);
    const lateOpen = await page.$('#lateModalMinutes');
    check('late modal opened', !!lateOpen, '');
    if (lateOpen) {
      await page.fill('#lateModalMinutes', '5').catch(() => {});
      await page.click('#lateModal form button[type=submit]').catch(() => {});
      await waitNav(4000);
    }
    const atts = await qaDb('attsched' + scheduleId);
    const row = atts.find(a => a.student_id === studentId);
    check('late marked in DB', row && row.status === 'late', 'status=' + (row && row.status) + ' min=' + (row && row.late_minutes));
  } else { check('late mark skipped', false, 'no schedule'); }
} catch (e) { recordError('attendance mark late', e); }

console.log('ATTENDANCE DONE. Passed: ' + passed + '/' + (passed + failed));

// cleanup
try {
  if (scheduleId) {
    const r = await qaDb('cleanupt3att');
    check('cleanup: QA schedules/attendances removed', r === true || r === 'true', 'res=' + r);
  }
} catch (e) { recordError('cleanup', e); }

await browser.close();