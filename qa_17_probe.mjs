import { newBrowser } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
const B = 'http://qa-center-194003.localhost:8000';
const { execSync } = await import('node:child_process');
const php = (s) => execSync('php "D:\\new project\\antigravty\\taalimu.com\\taalimu.com\\qa_db_helpers.php" ' + s, { encoding: 'utf8' }).trim();
try {
  await page.goto(B + '/login', { waitUntil: 'domcontentloaded', timeout: 30000 });
  await page.waitForTimeout(2500);
  const tok = await page.evaluate(() => document.querySelector('form input[name=_token]')?.value);
  const res = await page.evaluate(async ({ tk }) => {
    const r = await fetch('/login', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': tk }, body: new URLSearchParams({ email: 'qa.owner194003@example.com', password: 'password', _token: tk }).toString(), redirect: 'manual' });
    return { status: r.status, loc: r.headers.get('location') };
  }, { tk: tok });
  await page.goto(B + (res.loc && !res.loc.includes('/login') ? res.loc : '/'), { waitUntil: 'domcontentloaded', timeout: 30000 }).catch(() => {});
  await page.waitForTimeout(3000);
  const sid = JSON.parse(php('lastsched')).id;
  await page.goto(B + '/schedules/' + sid + '/edit', { waitUntil: 'domcontentloaded', timeout: 30000 });
  await page.waitForTimeout(3000);
  await page.fill('input[name=start_time]', '10:00');
  await page.fill('input[name=end_time]', '11:00');
  await page.fill('input[name=max_students]', '18');
  const sub = await page.evaluate(async () => {
    const f = [...document.querySelectorAll('form')].find(f => f.action.endsWith('/schedules/' + location.pathname.split('/')[2]));
    if (!f) return { ok: false, why: 'no form' };
    const fd = new FormData(f);
    const tok = f.querySelector('input[name=_token]').value;
    const r = await fetch(f.action, { method: 'POST', headers: { 'X-CSRF-TOKEN': tok }, body: fd, redirect: 'manual' });
    return { status: r.status, loc: r.headers.get('location'), type: r.type };
  });
  console.log('SUB: ' + JSON.stringify(sub));
  await page.waitForTimeout(3000);
  console.log('DB: ' + php('sched' + sid));
} catch (e) { console.log('ERR: ' + e.message.slice(0, 300)); }
await browser.close();
