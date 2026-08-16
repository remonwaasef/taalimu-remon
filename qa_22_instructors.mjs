import { newBrowser, login, check, recordError, dumpJson, BASE } from './qa_helpers.mjs';

const { browser, ctx, page } = await newBrowser();
const suffix = Date.now().toString().slice(-6);
const iname = 'QA Instructor ' + suffix;
const iemail = 'qa.instructor.' + suffix + '@demo.test';
const iphone = '051' + String(Math.floor(1000000 + Math.random() * 8999999));

try {
  await login(page);
  check('login lands on dashboard', page.url().endsWith(':8000/') || page.url().endsWith('/dashboard'), page.url().slice(0, 80));

  // --- CREATE ---
  await page.goto(BASE + '/instructors/create', { waitUntil: 'domcontentloaded' });
  const f = page.locator('form[action*="/instructors"], form[method="post"]').filter({ has: page.locator('input[name="name"]') }).first();
  check('instructors.create page has form', await f.count() === 1);
  if (await f.count()) {
    await f.locator('input[name="name"]').fill(iname);
    if (await f.locator('input[name="email"]').count()) await f.locator('input[name="email"]').fill(iemail);
    if (await f.locator('input[name="phone"]').count()) await f.locator('input[name="phone"]').fill(iphone);
    if (await f.locator('input[name="specialization"]').count()) await f.locator('input[name="specialization"]').fill('QA التخصص');
    if (await f.locator('select[name="commission_type"]').count()) {
      const opts = await f.locator('select[name="commission_type"] option').count();
      if (opts > 1) await f.locator('select[name="commission_type"]').selectOption({ index: 1 });
    }
    if (await f.locator('input[name="commission_rate"]').count()) await f.locator('input[name="commission_rate"]').fill('10');
    const navP = page.waitForNavigation({ waitUntil: 'commit', timeout: 30000 }).catch(() => {});
    await f.locator('button[type="submit"]').click();
    await navP;
    await page.waitForTimeout(1500);
    check('instructors.store redirects to index', page.url().endsWith('/instructors'), page.url().slice(0, 90));

    const listTxt = await page.locator('body').innerText();
    check('instructors.index shows created instructor', listTxt.includes(iname));

    // --- find id ---
    let insId = null;
    const row = page.locator('tr', { hasText: iname }).first();
    if (await row.count()) {
      const link = row.locator('a[href*="/instructors/"]').first();
      const href = await link.getAttribute('href').catch(() => null);
      const m = href && href.match(/\/instructors\/(\d+)/);
      if (m) insId = m[1];
    }
    check('instructor id found in row', !!insId, 'id=' + insId);

    if (insId) {
      // --- SHOW ---
      const rShow = await page.goto(BASE + '/instructors/' + insId, { waitUntil: 'domcontentloaded' });
      const sTxt = await page.locator('body').innerText();
      check('instructors.show renders (200, no error)', rShow.status() === 200 && !sTxt.includes('Server Error'), 'status=' + rShow.status());

      // --- TOGGLE STATUS (was 500 pre-fix: BUG-020) ---
      await page.goto(BASE + '/instructors', { waitUntil: 'domcontentloaded' });
      const before = await page.evaluate(async (id) => {
        const csrf = document.cookie.split(';').map(x => x.trim().split('=')).reduce((o, [k, v]) => (o[k] = decodeURIComponent(v), o), {});
        const r = await fetch('/instructors/' + id + '/toggle-status', { method: 'POST', headers: { 'X-XSRF-TOKEN': csrf['XSRF-TOKEN'], 'Accept': 'application/json' } });
        return r.status;
      }, insId);
      await page.goto(BASE + '/instructors', { waitUntil: 'domcontentloaded' });
      await page.waitForTimeout(1000);
      const rowT = page.locator('tr', { hasText: iname }).first();
      const badge1 = await rowT.locator('span.badge').first().innerText().catch(() => '');
      const after = await page.evaluate(async (id) => {
        const csrf = document.cookie.split(';').map(x => x.trim().split('=')).reduce((o, [k, v]) => (o[k] = decodeURIComponent(v), o), {});
        const r = await fetch('/instructors/' + id + '/toggle-status', { method: 'POST', headers: { 'X-XSRF-TOKEN': csrf['XSRF-TOKEN'], 'Accept': 'application/json' } });
        return r.status;
      }, insId);
      await page.goto(BASE + '/instructors', { waitUntil: 'domcontentloaded' });
      await page.waitForTimeout(1000);
      const rowT2 = page.locator('tr', { hasText: iname }).first();
      const badge2 = await rowT2.locator('span.badge').first().innerText().catch(() => '');
      check('toggle-status 200 + badge flips back', before === 200 && after === 200 && badge1 !== badge2,
        JSON.stringify({ before, after, badge1, badge2 }));

      // --- EDIT ---
      await page.goto(BASE + '/instructors/' + insId + '/edit', { waitUntil: 'domcontentloaded' });
      const ef = page.locator('form[method="post"]').filter({ has: page.locator('input[name="name"]') }).first();
      if (await ef.count()) {
        await ef.locator('input[name="name"]').fill(iname + ' EDITED');
        const navP2 = page.waitForNavigation({ waitUntil: 'commit', timeout: 20000 }).catch(() => {});
        await ef.locator('button[type="submit"]').click();
        await navP2;
        await page.waitForTimeout(1500);
        const listTxt2 = await page.locator('body').innerText();
        check('instructors.update persists', listTxt2.includes(iname + ' EDITED'));
      } else {
        check('instructors.edit has form', false);
      }

      // --- DESTROY ---
      await page.goto(BASE + '/instructors', { waitUntil: 'domcontentloaded' });
      await page.waitForTimeout(1200);
      const delForm = page.locator('form[action*="/instructors/' + insId + '"]').first();
      if (await delForm.count()) {
        const delRes = await page.evaluate(async (id) => {
          const forms = [...document.querySelectorAll('form[action*="/instructors/' + id + '"]')];
          const f = forms.find(x => x.querySelector('input[name="_method"]')?.value === 'DELETE') || forms[0];
          const csrf = f.querySelector('input[name="_token"]').value;
          const r = await fetch(f.getAttribute('action'), { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }, body: new URLSearchParams({ _method: 'DELETE' }) });
          return r.status;
        }, insId);
        await page.waitForTimeout(2000);
        await page.goto(BASE + '/instructors', { waitUntil: 'domcontentloaded' });
        await page.waitForTimeout(1000);
        const listAfter = await page.locator('body').innerText();
        check('instructors.destroy removes from list', delRes === 200 && !listAfter.includes(iname + ' EDITED') && !listAfter.includes(iname),
          'http=' + delRes);
      } else {
        check('instructors.destroy form found', false);
      }
    }
  }
} catch (e) {
  recordError('qa22_instructors', e);
}

console.log('\n--- CONSOLE ERRORS (first 5) ---');
console.log((page._consoleErrors || []).slice(0, 5).join('\n') || 'none');
await dumpJson('22_instructors');
await browser.close();