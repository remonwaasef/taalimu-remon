import { newBrowser, login, check, recordError, dumpJson, BASE } from './qa_helpers.mjs';

const { browser, ctx, page } = await newBrowser();

try {
  await login(page);

  // ================= SETTINGS =================
  await page.goto(BASE + '/settings', { waitUntil: 'domcontentloaded' });
  const sTxt = await page.locator('body').innerText();
  check('settings.index loads', !sTxt.includes('Server Error'));
  const sf = page.locator('form[action*="/settings"]').first();
  check('settings page has form', await sf.count() >= 1);
  if (await sf.count()) {
    const nameInput = sf.locator('input[name*="name"], input[name="site_name"], input[name="center_name"]').first();
    if (await nameInput.count()) {
      const oldVal = await nameInput.inputValue();
      await nameInput.fill(oldVal + ' ');
      const navP = page.waitForNavigation({ waitUntil: 'commit', timeout: 20000 }).catch(() => {});
      await sf.locator('button[type="submit"]').first().click();
      await navP;
      await page.waitForTimeout(1200);
      const after = await page.locator('body').innerText();
      check('settings.update submits (200, no 500)', !after.includes('Server Error'), 'url=' + page.url().slice(0, 60));
    } else {
      check('settings form name field', false);
    }
  }

  // ================= PROFILE =================
  await page.goto(BASE + '/profile', { waitUntil: 'domcontentloaded' });
  const pTxt = await page.locator('body').innerText();
  check('profile page loads', !pTxt.includes('Server Error') && pTxt.includes('الملف الشخصي'));
  const pf = page.locator('form[action*="/profile"]').filter({ has: page.locator('input[name="name"]') }).first();
  if (await pf.count()) {
    const nameInput = pf.locator('input[name="name"]');
    const oldVal = await nameInput.inputValue();
    await nameInput.fill(oldVal);
    const navP = page.waitForNavigation({ waitUntil: 'commit', timeout: 20000 }).catch(() => {});
    await pf.locator('button[type="submit"]').first().click();
    await navP;
    await page.waitForTimeout(1200);
    const after = await page.locator('body').innerText();
    check('profile.update submits without error', !after.includes('Server Error') && !after.includes('Whoops'));
  } else {
    check('profile form found', false);
  }

  // ================= NOTIFICATIONS =================
  await page.goto(BASE + '/notifications', { waitUntil: 'domcontentloaded' });
  const nTxt = await page.locator('body').innerText();
  check('notifications.index loads', !nTxt.includes('Server Error'));
  const markAll = await page.evaluate(async () => {
    const csrf = document.cookie.split(';').map(x => x.trim().split('=')).reduce((o, [k, v]) => (o[k] = decodeURIComponent(v), o), {});
    const f = [...document.querySelectorAll('form[action*="read-all"]')][0];
    const token = f ? f.querySelector('input[name="_token"]').value : csrf['XSRF-TOKEN'];
    const r = await fetch('/notifications/read-all', { method: 'POST', headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' } });
    return r.status;
  });
  check('notifications.readAll accepted', markAll === 200 || markAll === 302, 'http=' + markAll);

  // ================= BILLING =================
  await page.goto(BASE + '/billing', { waitUntil: 'domcontentloaded' });
  const bTxt = await page.locator('body').innerText();
  check('billing.index loads', !bTxt.includes('Server Error') && bTxt.includes('الفواتير'));

  // ================= EXPENSES =================
  await page.goto(BASE + '/expenses', { waitUntil: 'domcontentloaded' });
  const eTxt = await page.locator('body').innerText();
  check('expenses.index loads', !eTxt.includes('Server Error') && eTxt.includes('المصروفات'));

  await page.goto(BASE + '/expenses/create', { waitUntil: 'domcontentloaded' });
  const ef = page.locator('form[action*="/expenses"]').filter({ has: page.locator('input[name*="amount"], input[name="title"]') }).first();
  check('expenses.create has form', await ef.count() === 1);
  if (await ef.count()) {
    const suffix = Date.now().toString().slice(-5);
    const cat = ef.locator('input[name="category"], select[name="category"]').first();
    if (await cat.count()) {
      if (await cat.evaluate(e => e.tagName === 'SELECT')) await cat.selectOption({ index: 1 });
      else await cat.fill('QA Category ' + suffix);
    }
    const am = ef.locator('input[name="amount"]').first();
    if (await am.count()) await am.fill('150');
    const dt = ef.locator('input[name="date"]').first();
    if (await dt.count()) await dt.fill('2026-08-14');
    const pm = ef.locator('select[name="payment_method"], input[name="payment_method"]').first();
    if (await pm.count()) {
      if (await pm.evaluate(e => e.tagName === 'SELECT')) await pm.selectOption({ index: 1 });
      else await pm.fill('cash');
    }
    const navP = page.waitForNavigation({ waitUntil: 'commit', timeout: 20000 }).catch(() => {});
    await ef.locator('button[type="submit"]').first().click();
    await navP;
    await page.waitForTimeout(1500);
    const eList = await page.locator('body').innerText();
    check('expenses.store creates expense', eList.includes('QA Category ' + suffix), 'url=' + page.url().slice(0, 60));
  }
} catch (e) {
  recordError('qa26_settings_profile', e);
}

console.log('\n--- CONSOLE ERRORS (first 5) ---');
console.log((page._consoleErrors || []).slice(0, 5).join('\n') || 'none');
await dumpJson('26_settings_profile');
await browser.close();