import { newBrowser, login, check, recordError, dumpJson, BASE } from './qa_helpers.mjs';

const { browser, ctx, page } = await newBrowser();
const suffix = Date.now().toString().slice(-6);
const cname = 'QA Classroom ' + suffix;
const aname = 'QA Asset ' + suffix;

try {
  await login(page);

  // ================= CLASSROOMS =================
  await page.goto(BASE + '/classrooms', { waitUntil: 'domcontentloaded' });
  const rIdx = await page.locator('body').innerText().catch(() => '');
  check('classrooms.index loads', !rIdx.includes('Server Error') && !rIdx.includes('Whoops'));

  await page.goto(BASE + '/classrooms/create', { waitUntil: 'domcontentloaded' });
  const cf = page.locator('form[action*="/classrooms"]').filter({ has: page.locator('input[name="name"]') }).first();
  check('classrooms.create has form', await cf.count() === 1);
  if (await cf.count()) {
    await cf.locator('input[name="name"]').fill(cname);
    if (await cf.locator('input[name="capacity"]').count()) await cf.locator('input[name="capacity"]').fill('25');
    const navP = page.waitForNavigation({ waitUntil: 'commit', timeout: 30000 }).catch(() => {});
    await cf.locator('button[type="submit"]').click();
    await navP;
    await page.waitForTimeout(1500);
    check('classrooms.store redirects to index', page.url().endsWith('/classrooms'), page.url().slice(0, 90));
    const listTxt = await page.locator('body').innerText();
    check('classrooms.index shows created classroom', listTxt.includes(cname));

    let cid = null;
    const row = page.locator('tr', { hasText: cname }).first();
    if (await row.count()) {
      const link = row.locator('a[href*="/classrooms/"]').first();
      const href = await link.getAttribute('href').catch(() => null);
      const m = href && href.match(/\/classrooms\/(\d+)/);
      if (m) cid = m[1];
    }
    check('classroom id found in row', !!cid, 'id=' + cid);

    if (cid) {
      const rShow = await page.goto(BASE + '/classrooms/' + cid, { waitUntil: 'domcontentloaded' });
      const sTxt = await page.locator('body').innerText();
      check('classrooms.show renders', rShow.status() === 200 && !sTxt.includes('Server Error'), 'status=' + rShow.status());

      // destroy via DELETE on the form in the show page or list
      await page.goto(BASE + '/classrooms', { waitUntil: 'domcontentloaded' });
      await page.waitForTimeout(1000);
      const delRes = await page.evaluate(async (id) => {
        const forms = [...document.querySelectorAll('form[action*="/classrooms/' + id + '"]')];
        const f = forms.find(x => x.querySelector('input[name="_method"]')?.value === 'DELETE') || forms[0];
        if (!f) return 'noform';
        const csrf = f.querySelector('input[name="_token"]').value;
        const r = await fetch(f.getAttribute('action'), { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }, body: new URLSearchParams({ _method: 'DELETE' }) });
        return r.status;
      }, cid);
      await page.waitForTimeout(1500);
      await page.goto(BASE + '/classrooms', { waitUntil: 'domcontentloaded' });
      await page.waitForTimeout(1000);
      const listAfter = await page.locator('body').innerText();
      check('classrooms.destroy removes from list', delRes === 200 && !listAfter.includes(cname), 'http=' + delRes);
    }
  }

  // ================= ASSETS / INVENTORY =================
  await page.goto(BASE + '/inventory', { waitUntil: 'domcontentloaded' });
  const aIdx = await page.locator('body').innerText().catch(() => '');
  check('inventory.index loads', !aIdx.includes('Server Error') && !aIdx.includes('Whoops'));

  await page.goto(BASE + '/inventory/create', { waitUntil: 'domcontentloaded' });
  const af = page.locator('form[action*="/inventory"], form[action*="/assets"]').filter({ has: page.locator('input[name="name"]') }).first();
  check('inventory.create has form', await af.count() === 1);
  if (await af.count()) {
    await af.locator('input[name="name"]').fill(aname);
    const navP = page.waitForNavigation({ waitUntil: 'commit', timeout: 30000 }).catch(() => {});
    await af.locator('button[type="submit"]').click();
    await navP;
    await page.waitForTimeout(1500);
    check('inventory.store redirects', page.url().includes('/inventory') || page.url().includes('/assets'), page.url().slice(0, 90));
    const aList = await page.locator('body').innerText();
    check('inventory.index shows created asset', aList.includes(aname));

    let aid = null;
    const arow = page.locator('tr', { hasText: aname }).first();
    if (await arow.count()) {
      const link = arow.locator('a[href*="/inventory/"], a[href*="/assets/"]').first();
      const href = await link.getAttribute('href').catch(() => null);
      const m = href && href.match(/\/(?:inventory|assets)\/(\d+)/);
      if (m) aid = m[1];
    }
    check('asset id found in row', !!aid, 'id=' + aid);

    if (aid) {
      await page.goto(BASE + '/inventory', { waitUntil: 'domcontentloaded' });
      await page.waitForTimeout(1000);
      const delRes = await page.evaluate(async (id) => {
        const forms = [...document.querySelectorAll('form[action*="/inventory/' + id + '"], form[action*="/assets/' + id + '"]')];
        const f = forms.find(x => x.querySelector('input[name="_method"]')?.value === 'DELETE') || forms[0];
        if (!f) return 'noform';
        const csrf = f.querySelector('input[name="_token"]').value;
        const r = await fetch(f.getAttribute('action'), { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }, body: new URLSearchParams({ _method: 'DELETE' }) });
        return r.status;
      }, aid);
      await page.waitForTimeout(1500);
      await page.goto(BASE + '/inventory', { waitUntil: 'domcontentloaded' });
      await page.waitForTimeout(1000);
      const aListAfter = await page.locator('body').innerText();
      check('inventory.destroy removes from list', delRes === 200 && !aListAfter.includes(aname), 'http=' + delRes);
    }
  }
} catch (e) {
  recordError('qa23_classrooms_assets', e);
}

console.log('\n--- CONSOLE ERRORS (first 5) ---');
console.log((page._consoleErrors || []).slice(0, 5).join('\n') || 'none');
await dumpJson('23_classrooms_assets');
await browser.close();