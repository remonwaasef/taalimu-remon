import { newBrowser, login, fetchAs, BASE } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
page.setDefaultTimeout(20000);
await login(page);
const r = await fetchAs(page, BASE + '/students/search?q=' + encodeURIComponent('طالب اختبار QA معدل'));
const s = Array.isArray(r.body) && r.body.length ? r.body[0] : null;
console.log('edited student:', JSON.stringify(s));
await page.goto(BASE + '/students');
await page.waitForTimeout(1500);
const row = page.locator('tr', { hasText: 'طالب اختبار QA معدل' }).first();
console.log('row count:', await row.count());
const dd = row.locator('.dropdown', { has: row.locator('button[data-confirm-delete]') });
console.log('action dropdown count:', await dd.count());
await dd.locator('[data-bs-toggle="dropdown"]').first().click();
await page.waitForTimeout(800);
const delBtn = row.locator('button[data-confirm-delete]');
console.log('delBtn count:', await delBtn.count(), 'visible:', await delBtn.isVisible().catch(() => false));
const menuOpen = await row.locator('.dropdown-menu.show').count();
console.log('menu open:', menuOpen);
if (await delBtn.count()) {
  await delBtn.first().click({ timeout: 5000 }).catch((e) => console.log('click err:', String(e).split('\n')[0]));
  await page.waitForTimeout(1200);
  const swal = await page.locator('.swal2-popup').isVisible().catch(() => false);
  console.log('swal visible:', swal);
  if (swal) {
    console.log('swal confirm btn count:', await page.locator('.swal2-confirm').count());
    await page.locator('.swal2-confirm').click().catch((e) => console.log('swal click err:', String(e).split('\n')[0]));
    await page.waitForTimeout(2500);
    console.log('after confirm url:', page.url());
  }
}
await browser.close();