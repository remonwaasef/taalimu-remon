import { newBrowser } from './qa_helpers.mjs';
import { execSync } from 'node:child_process';

const BASE = 'D:\\new project\\antigravty\\taalimu.com\\taalimu.com';
const php = (script) => execSync('php "' + BASE + '\\qa_db_helpers.php" ' + script, { encoding: 'utf8' }).trim();
const qaDb = async (script) => JSON.parse(php(script));
const B = process.env.QA_BASE_URL || 'http://qa-center-194003.localhost:8000';
const A = (p) => B + p;
const { browser, page } = await newBrowser();

let passed = 0, failed = 0;
const check = (name, ok, info) => {
  console.log(`[${ok ? 'PASS' : 'FAIL'}] ${name} :: ${info ?? ''}`);
  ok ? passed++ : failed++;
};
const recordError = (label, e) => {
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

const fillSelect = async (name, value) => {
  const ok = await page.evaluate(({ name, value }) => {
    const el = document.querySelector('select[name="' + name + '"]');
    if (!el) return 'no-select';
    el.value = String(value);
    el.dispatchEvent(new Event('change', { bubbles: true }));
    const ts = window.TomSelect && el.tomselect;
    if (ts) { ts.setValue(String(value)); }
    return 'ok:' + el.value;
  }, { name, value });
  return ok;
};

let fx = null;
try { php('cleanupt3att'); } catch (e) {}
try { fx = await qaDb('ensure_t3_att_fixtures'); } catch (e) { console.log('  [dbg] fixture: ' + e.message.slice(0, 80)); }

try {
  const url = await login('qa.owner194003@example.com', 'password');
  check('admin logged in', !url.includes('/login'), 'url=' + url);
} catch (e) { recordError('admin login', e); }

// create page
try {
  const r = await page.goto(A('/schedules/create'), { waitUntil: 'domcontentloaded', timeout: 30000 });
  await page.waitForTimeout(2500);
  const hasForm = await page.$('form[action$="/schedules"]');
  check('schedule create page 200 + form', r.status() === 200 && !!hasForm, 'status=' + r.status());
} catch (e) { recordError('schedule create page', e); }

// create via UI form
let schedId = null;
try {
  if (fx) {
    await fillSelect('course_id', '3');
    await fillSelect('classroom_id', String(fx.classroom));
    await fillSelect('instructor_id', String(fx.instructor));
    await fillSelect('day_of_week', String(new Date().getDay()));
    await page.fill('input[name=start_time]', '08:00');
    await page.fill('input[name=end_time]', '09:00');
    await page.fill('input[name=max_students]', '15');
    await page.click('form[action$="/schedules"] button[type=submit]');
    await waitNav(4000);
    const after = await page.evaluate(() => location.href);
    const s = await qaDb('lastsched');
    schedId = s && s.id;
    check('schedule created via UI + redirect', !!schedId && after.includes('/schedules'), 'id=' + schedId + ' url=' + after);
    const row = schedId ? await qaDb('sched' + schedId) : null;
    check('schedule persisted with fields', !!row && row.day_of_week !== null && String(row.start_time).startsWith('08:00'), 'start=' + (row && row.start_time));
  } else { check('schedule create skipped', false, 'no fixture'); }
} catch (e) { recordError('schedule create', e); }

// conflict detection: same classroom + overlapping time on same day
try {
  if (fx) {
    await page.goto(A('/schedules/create'), { waitUntil: 'domcontentloaded', timeout: 30000 });
    await page.waitForTimeout(2500);
    await fillSelect('course_id', '4');
    await fillSelect('classroom_id', String(fx.classroom));
    await fillSelect('instructor_id', String(fx.instructor));
    await fillSelect('day_of_week', String(new Date().getDay()));
    await page.fill('input[name=start_time]', '08:30');
    await page.fill('input[name=end_time]', '09:30');
    await page.fill('input[name=max_students]', '10');
    await page.click('form[action$="/schedules"] button[type=submit]');
    await waitNav(4000);
    const conflict = await page.evaluate(() => {
      const el = document.querySelector('.alert-danger');
      return el ? el.innerText.trim().slice(0, 120) : '';
    });
    check('conflict detection blocks overlapping schedule', conflict.length > 0, 'msg=' + conflict);
    const s = await qaDb('lastsched');
    check('conflicting schedule NOT created', !s || s.id === schedId, 'lastsched=' + (s && s.id));
  } else { check('conflict check skipped', false, 'no fixture'); }
} catch (e) { recordError('conflict detection', e); }

// edit + update
try {
  if (schedId) {
    const r = await page.goto(A('/schedules/' + schedId + '/edit'), { waitUntil: 'domcontentloaded', timeout: 30000 });
    await page.waitForTimeout(2500);
    check('schedule edit page 200', r.status() === 200, 'status=' + r.status());
    await page.fill('input[name=start_time]', '10:00');
    await page.fill('input[name=end_time]', '11:00');
    await page.fill('input[name=max_students]', '18');
    await page.click('form[action$="/schedules/' + schedId + '"] button[type=submit]');
    await waitNav(4000);
    const row = await qaDb('sched' + schedId);
    check('schedule updated in DB', row && String(row.start_time).startsWith('10:00') && row.max_students === 18, 'start=' + (row && row.start_time) + ' max=' + (row && row.max_students));
  } else { check('schedule update skipped', false, 'no sched'); }
} catch (e) { recordError('schedule update', e); }

// destroy
try {
  if (schedId) {
    await page.goto(A('/schedules'), { waitUntil: 'domcontentloaded', timeout: 30000 });
    await page.waitForTimeout(2500);
    page.on('dialog', async (d) => { await d.accept().catch(() => {}); }).catch ? null : null;
    const gone = await page.evaluate(async (id) => {
      const form = document.querySelector('form[action$="/schedules/' + id + '"]');
      if (!form) return 'no-form';
      const tok = form.querySelector('input[name=_token]').value;
      const fd = new FormData(form);
      const r = await fetch(form.action, { method: 'POST', headers: { 'X-CSRF-TOKEN': tok }, body: fd, redirect: 'manual' });
      return r.status;
    }, schedId);
    await waitNav(4000);
    const row = await qaDb('sched' + schedId);
    check('schedule destroyed via form', row === null, 'row=' + JSON.stringify(row));
  } else { check('schedule destroy skipped', false, 'no sched'); }
} catch (e) { recordError('schedule destroy', e); }

console.log('SCHEDULES DONE. Passed: ' + passed + '/' + (passed + failed));

try {
  const r = await qaDb('cleanupt3att');
  check('cleanup: QA fixtures removed', r === true || r === 'true', 'res=' + r);
} catch (e) { recordError('cleanup', e); }

await browser.close();