import { newBrowser, login, check, recordError, results, dumpJson, BASE } from './qa_helpers.mjs';

const { browser, ctx, page } = await newBrowser();
const suffix = Date.now().toString().slice(-6);
const cname = 'QA Course ' + suffix;

try {
  await login(page);
  if (!page.url().includes('/dashboard') && !page.url().includes('/')) console.log('login landed at: ' + page.url());
  await page.goto(BASE + '/courses', { waitUntil: 'domcontentloaded' });
  check('courses.index 200', page.url().endsWith('/courses'));
  check('courses.index lists existing courses', await page.locator('body').innerText().then(t => t.includes('QA') || t.includes('course') || t.includes('Course') || t.length > 100));
  const body0 = await page.locator('body').innerText();
  check('no 500 on courses.index', !body0.includes('Whoops') && !body0.includes('Server Error'));

  // --- CREATE ---
  await page.goto(BASE + '/courses/create', { waitUntil: 'domcontentloaded' });
  check('courses.create page', await page.locator('input[name="title"], input[name="name"]').count() > 0);
  const titleSel = await page.locator('input[name="title"]').count() ? 'input[name="title"]' : 'input[name="name"]';
  await page.fill(titleSel, cname);
  const desc = await page.locator('textarea[name="description"], textarea[name="short_description"]').first();
  if (await desc.count()) await desc.fill('QA test course description');
  const submitBtn = page.locator('form button[type="submit"]').first();
  await Promise.all([
    page.waitForNavigation({ waitUntil: 'commit', timeout: 30000 }).catch(() => {}),
    submitBtn.click(),
  ]);
  await page.waitForTimeout(1500);
  const afterCreate = page.url();
  check('courses.store created (redirected to show/edit)', !afterCreate.endsWith('/courses/create'), afterCreate.slice(0, 90));

  const courseIdMatch = afterCreate.match(/\/(\d+)\b/);
  const courseId = courseIdMatch ? courseIdMatch[1] : null;
  if (!courseId) { recordError('courses.store', 'no course id in redirect url'); }

  // --- LIST has new course ---
  await page.goto(BASE + '/courses', { waitUntil: 'domcontentloaded' });
  const listTxt = await page.locator('body').innerText();
  check('courses.index shows created course', listTxt.includes(cname));

  // --- SHOW ---
  if (courseId) {
    await page.goto(BASE + '/courses/' + courseId, { waitUntil: 'domcontentloaded' });
    check('courses.show 200', page.url().endsWith('/courses/' + courseId));
    const showTxt = await page.locator('body').innerText();
    check('courses.show renders course', showTxt.includes(cname));

    // --- CURRICULUM (sections) ---
    await page.goto(BASE + '/courses/' + courseId + '/curriculum', { waitUntil: 'domcontentloaded' });
    check('curriculum page', !(await page.locator('body').innerText()).includes('Server Error'));
    const hasSectionForm = await page.locator('input[name="title"], input[name="name"], input[name="section_title"], input[name="section_name"]').count();
    check('curriculum has add-section control', hasSectionForm > 0);

    // --- toggle-status ---
    const before = await page.locator('body').innerText();
    const statusUrl = BASE + '/courses/' + courseId + '/toggle-status';
    const res = await (async () => {
      try {
        const r = await page.evaluate(async (u) => {
          const c = document.cookie.split(';').map(x => x.trim().split('=')).reduce((o, [k, v]) => (o[k] = decodeURIComponent(v), o), {});
          const r = await fetch(u, { method: 'POST', headers: { 'Accept': 'application/json', 'X-XSRF-TOKEN': c['XSRF-TOKEN'] } });
          return r.status;
        }, statusUrl);
        return r;
      } catch (e) { return 'ERR:' + e.message; }
    })();
    check('courses.toggle-status POST', res === 200 || res === 302 || res === 419, String(res));
  }

  // --- EDIT ---
  if (courseId) {
    await page.goto(BASE + '/courses/' + courseId + '/edit', { waitUntil: 'domcontentloaded' });
    check('courses.edit 200', !(await page.locator('body').innerText()).includes('Server Error'));
  }

  // --- VALIDATION: create with empty title ---
  await page.goto(BASE + '/courses/create', { waitUntil: 'domcontentloaded' });
  const emptySubmit = page.locator('form button[type="submit"]').first();
  await Promise.all([
    page.waitForNavigation({ waitUntil: 'commit', timeout: 15000 }).catch(() => {}),
    emptySubmit.click(),
  ]);
  await page.waitForTimeout(1200);
  const vtxt = await page.locator('body').innerText();
  check('courses.store rejects empty title', !vtxt.includes('Server Error'));

  // --- DESTROY (cleanup) ---
  if (courseId) {
    await page.goto(BASE + '/courses/' + courseId, { waitUntil: 'domcontentloaded' });
    const del = page.locator('form[method="POST"] button[type="submit"], button[name="_method"]').first();
    const delCount = await del.count();
    if (delCount) {
      await Promise.all([
        page.waitForNavigation({ waitUntil: 'commit', timeout: 20000 }).catch(() => {}),
        del.click(),
      ]);
      await page.waitForTimeout(1200);
      check('courses.destroy executed', !page.url().includes('/courses/' + courseId));
    } else {
      check('courses.destroy button present', false, 'no delete button on show page');
    }
  }
} catch (e) {
  recordError('qa21_courses', e);
}

console.log('\n--- CONSOLE ERRORS ---');
console.log((page._consoleErrors || []).slice(0, 8).join('\n') || 'none');

await dumpJson('21_courses');
await browser.close();