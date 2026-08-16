import { newBrowser, login, check, recordError, dumpJson, BASE } from './qa_helpers.mjs';

const { browser, ctx, page } = await newBrowser();
const suffix = Date.now().toString().slice(-6);
const uname = 'QA User ' + suffix;
const uemail = 'qa.user.' + suffix + '@demo.test';
const rname = 'QA Role ' + suffix;

try {
  await login(page);

  // ================= USERS =================
  await page.goto(BASE + '/users', { waitUntil: 'domcontentloaded' });
  const uIdx = await page.locator('body').innerText();
  check('users.index loads', !uIdx.includes('Server Error') && uIdx.includes('المستخدمين') || uIdx.includes('Users'), 'title=' + uIdx.slice(0, 40));

  await page.goto(BASE + '/users/create', { waitUntil: 'domcontentloaded' });
  const uf = page.locator('form[action*="/users"], form[method="post"]').filter({ has: page.locator('input[name="name"]') }).first();
  check('users.create has form', await uf.count() === 1);
  if (await uf.count()) {
    await uf.locator('input[name="name"]').fill(uname);
    if (await uf.locator('input[name="email"]').count()) await uf.locator('input[name="email"]').fill(uemail);
    if (await uf.locator('select[name="role"]').count()) {
      const ropts = await uf.locator('select[name="role"] option').count();
      if (ropts > 1) await uf.locator('select[name="role"]').selectOption({ index: 1 });
    }
    const navP = page.waitForNavigation({ waitUntil: 'commit', timeout: 30000 }).catch(() => {});
    await uf.locator('button[type="submit"]').click();
    await navP;
    await page.waitForTimeout(1500);
    check('users.store redirects', page.url().includes('/users'), page.url().slice(0, 90));
    const uList = await page.locator('body').innerText();
    check('users.index shows created user', uList.includes(uname));

    // destroy the created user
    await page.waitForTimeout(800);
    const delRes = await page.evaluate(async (name) => {
      const rows = [...document.querySelectorAll('tr')].filter(r => r.textContent.includes(name));
      if (!rows.length) return 'norow';
      const row = rows[0];
      const form = row.querySelector('form[method="post"]');
      if (!form) return 'noform';
      const csrf = form.querySelector('input[name="_token"]').value;
      const action = form.getAttribute('action');
      const r = await fetch(action, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }, body: new URLSearchParams({ _method: 'DELETE' }) });
      return r.status;
    }, uname);
    await page.waitForTimeout(1500);
    await page.goto(BASE + '/users', { waitUntil: 'domcontentloaded' });
    await page.waitForTimeout(800);
    const uList2 = await page.locator('body').innerText();
    check('users.destroy removes user', delRes === 200 && !uList2.includes(uname), 'http=' + delRes);
  }

  // ================= ROLES =================
  await page.goto(BASE + '/roles', { waitUntil: 'domcontentloaded' });
  const rIdx = await page.locator('body').innerText();
  check('roles.index loads', !rIdx.includes('Server Error'));

  await page.goto(BASE + '/roles/create', { waitUntil: 'domcontentloaded' });
  const rf = page.locator('form[action*="/roles"], form[method="post"]').filter({ has: page.locator('input[name="name"]') }).first();
  check('roles.create has form', await rf.count() === 1);
  if (await rf.count()) {
    await rf.locator('input[name="name"]').fill(rname);
    const navP = page.waitForNavigation({ waitUntil: 'commit', timeout: 30000 }).catch(() => {});
    await rf.locator('button[type="submit"]').click();
    await navP;
    await page.waitForTimeout(1500);
    const rList = await page.locator('body').innerText();
    check('roles.store creates role', rList.includes(rname));
  }
} catch (e) {
  recordError('qa25_users_roles', e);
}

console.log('\n--- CONSOLE ERRORS (first 5) ---');
console.log((page._consoleErrors || []).slice(0, 5).join('\n') || 'none');
await dumpJson('25_users_roles');
await browser.close();