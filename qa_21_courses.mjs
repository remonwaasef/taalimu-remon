import { newBrowser, login, check, recordError, results, dumpJson, BASE } from './qa_helpers.mjs';

const { browser, ctx, page } = await newBrowser();
const suffix = Date.now().toString().slice(-6);
const cname = 'QA Course ' + suffix;
const m = (Number(suffix.slice(0, 2)) % 58) + 1; // unique-ish 23:xx slot per run
const startT = '23:' + String(m).padStart(2, '0');
const endT = '23:' + String(m + 1).padStart(2, '0');

try {
  await login(page);
  check('login lands on dashboard', page.url().endsWith(':8000/') || page.url().endsWith('/dashboard'), page.url().slice(0, 80));

  // --- CREATE (full flow: schedules link validation) ---
  await page.goto(BASE + '/courses/create', { waitUntil: 'domcontentloaded' });
  const f = page.locator('form:has(input[name="price"])');
  check('create page has form', await f.count() === 1);
  await f.locator('input[name="title"]').fill(cname);
  await f.locator('input[name="price"]').fill('1500');
  await f.locator('input[name="sessions_count"]').fill('1');
  await f.locator('textarea[name="description"]').fill('QA creation flow');
  await page.waitForTimeout(1500);

  // --- VALIDATION: button stays disabled without schedules ---
  const disabledNoSched = await f.locator('button[type="submit"]').isDisabled();
  check('submit disabled until schedules complete (JS validation)', disabledNoSched === true);

  const items = await f.locator('.schedule-item').count();
  check('schedule item auto-added for sessions_count=1', items === 1, String(items));
  if (items > 0) {
    await f.locator('.schedule-item').first().locator('select[name*="day_of_week"]').selectOption({ index: 0 });
    const clsOptions = await f.locator('.schedule-item').first().locator('select[name*="classroom_id"] option').count();
    if (clsOptions > 1) await f.locator('.schedule-item').first().locator('select[name*="classroom_id"]').selectOption({ index: 1 });
    await f.locator('.schedule-item').first().locator('input[name*="start_time"]').fill(startT);
    await f.locator('.schedule-item').first().locator('input[name*="end_time"]').fill(endT);
    await page.waitForTimeout(2500);
  }
  const btnState = await f.locator('button[type="submit"]').evaluate(b => ({ disabled: b.disabled }));
  check('submit enabled after complete schedules', btnState.disabled === false, JSON.stringify(btnState));

  const navPromise = page.waitForNavigation({ waitUntil: 'commit', timeout: 30000 }).catch(() => {});
  await f.locator('button[type="submit"]').click();
  await navPromise;
  await page.waitForTimeout(1500);
  check('courses.store created (redirect to index)', page.url().endsWith('/courses'), page.url().slice(0, 90));

  // --- LIST shows created course + find its row-scoped id ---
  await page.goto(BASE + '/courses', { waitUntil: 'domcontentloaded' });
  await page.waitForTimeout(1000);
  const listTxt = await page.locator('body').innerText();
  check('courses.index shows created course', listTxt.includes(cname));

  let courseId = null;
  const row = page.locator('tr', { hasText: cname }).first();
  if (await row.count()) {
    const link = row.locator('a[href*="/courses/"]').first();
    const href = await link.getAttribute('href').catch(() => null);
    const m2 = href && href.match(/\/courses\/(\d+)/);
    if (m2) courseId = m2[1];
  }
  check('course id found in its own row', !!courseId, 'id=' + courseId);

  // --- SHOW + TOGGLE-STATUS (route previously 500: BUG-020) ---
  if (courseId) {
    await page.goto(BASE + '/courses/' + courseId, { waitUntil: 'domcontentloaded' });
    const showTxt = await page.locator('body').innerText();
    check('courses.show renders course title', showTxt.includes(cname), 'url=' + page.url().slice(0, 60));

    const postToggle = () => page.evaluate(async (courseId) => {
      try {
        const csrf = document.cookie.split(';').map(x => x.trim().split('=')).reduce((o, [k, v]) => (o[k] = decodeURIComponent(v), o), {});
        const r = await fetch('/courses/' + courseId + '/toggle-status', { method: 'POST', headers: { 'X-XSRF-TOKEN': csrf['XSRF-TOKEN'], 'Accept': 'application/json' } });
        return { status: r.status, body: String(r.status) };
      } catch (e) { return { status: 0, body: String(e).slice(0, 120) }; }
    }, courseId);

    const t1 = await postToggle();
    check('courses.toggle-status accepts POST (200/302)', t1.status === 200 || t1.status === 302, JSON.stringify(t1).slice(0, 100));

    await page.goto(BASE + '/courses', { waitUntil: 'domcontentloaded' });
    await page.waitForTimeout(1000);
    const rowAfter = page.locator('tr', { hasText: cname }).first();
    const badgeAfter = await rowAfter.locator('span.badge').first().innerText().catch(() => '');
    const t2 = await postToggle();
    await page.goto(BASE + '/courses', { waitUntil: 'domcontentloaded' });
    await page.waitForTimeout(1000);
    const rowBack = page.locator('tr', { hasText: cname }).first();
    const badgeBack = await rowBack.locator('span.badge').first().innerText().catch(() => '');
    check('toggle flips status badge & second toggle restores',
      badgeAfter.length > 0 && badgeAfter !== badgeBack,
      '1st=' + badgeAfter + ' 2nd=' + badgeBack);

    // --- CURRICULUM: add section ---
    await page.goto(BASE + '/courses/' + courseId + '/curriculum', { waitUntil: 'domcontentloaded' });
    const curTxt = await page.locator('body').innerText();
    check('curriculum page loads', !curTxt.includes('Server Error') && !curTxt.includes('Whoops'));
    const secForm = page.locator('form[action*="' + courseId + '/sections"]').first();
    if (await secForm.count()) {
      const secTitle = secForm.locator('input[name="title"]').first();
      if (await secTitle.count()) {
        await secTitle.fill('QA Section ' + suffix);
        const secNav = page.waitForNavigation({ waitUntil: 'commit', timeout: 20000 }).catch(() => {});
        await secForm.locator('button[type="submit"]').click();
        await secNav;
        await page.waitForTimeout(1200);
        await page.reload({ waitUntil: 'domcontentloaded' });
        await page.waitForTimeout(1000);
        const secInputs = await page.evaluate(() =>
          [...document.querySelectorAll('.section-item input[name="title"]')].map(i => i.value));
        check('section created and shown', secInputs.includes('QA Section ' + suffix), JSON.stringify(secInputs));
      }
    }

    // --- EDIT ---
    await page.goto(BASE + '/courses/' + courseId + '/edit', { waitUntil: 'domcontentloaded' });
    const editF = page.locator('form:has(input[name="price"])');
    check('courses.edit loads form', await editF.count() === 1);
    if (await editF.count()) {
      await editF.locator('input[name="title"]').fill(cname + ' EDITED');
      const editNav = page.waitForNavigation({ waitUntil: 'commit', timeout: 20000 }).catch(() => {});
      await editF.locator('button[type="submit"]').click();
      await editNav;
      await page.waitForTimeout(1500);
      const listTxt2 = await page.locator('body').innerText();
      check('courses.update persists', listTxt2.includes(cname + ' EDITED'));
    }
  } else {
    check('course id found', false, 'could not find created course');
  }

  // --- DESTROY (cleanup via index dropdown) + real absence check ---
  if (courseId) {
    await page.goto(BASE + '/courses', { waitUntil: 'domcontentloaded' });
    await page.waitForTimeout(1200);
    const drop = page.locator('form#delete-course-form-' + courseId).first();
    if (await drop.count()) {
      const delRes = await page.evaluate(async (formId) => {
        const f = document.getElementById(formId);
        const csrf = f.querySelector('input[name="_token"]').value;
        const action = f.getAttribute('action');
        const r = await fetch(action, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }, body: new URLSearchParams({ _method: 'DELETE' }) });
        return r.status;
      }, 'delete-course-form-' + courseId);
      await page.waitForTimeout(2000);
      await page.goto(BASE + '/courses', { waitUntil: 'domcontentloaded' });
      await page.waitForTimeout(1000);
      const listAfter = await page.locator('body').innerText();
      check('courses.destroy removes course (absent from list)', delRes === 200 && !listAfter.includes(cname),
        'delete http=' + delRes);
    } else {
      check('destroy form in index dropdown', false, 'delete form not found');
    }
  }
} catch (e) {
  recordError('qa21_courses', e);
}

console.log('\n--- CONSOLE ERRORS (first 5) ---');
console.log((page._consoleErrors || []).slice(0, 5).join('\n') || 'none');
await dumpJson('21_courses');
await browser.close();
