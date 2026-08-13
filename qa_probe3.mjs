import { newBrowser, login, BASE } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
const out = [];
try {
  await login(page, BASE);
  await page.goto(BASE + '/courses/1');
  await page.waitForTimeout(2500);
  const body = await page.locator('body').innerText();
  out.push('course page has enroll modal trigger: ' + body.includes('تسجيل طالب'));
  const btn = page.locator('button[data-bs-target="#enrollStudentModal"]');
  out.push('enroll button count: ' + await btn.count());
  if (await btn.count() > 0) {
    await btn.first().click();
    await page.waitForTimeout(1500);
    const modalVisible = await page.locator('#enrollStudentModal').isVisible().catch(() => false);
    out.push('modal visible: ' + modalVisible);
    const ts = page.locator('.ts-control');
    out.push('ts-control count: ' + await ts.count());
    if (await ts.count() > 0) {
      await ts.first().click();
      await page.waitForTimeout(2000);
      const options = await page.locator('.ts-dropdown .option').count();
      out.push('dropdown options: ' + options);
      if (options === 0) {
        const dd = await page.locator('.ts-dropdown').count();
        out.push('dropdown present: ' + dd);
        const html = await page.locator('.ts-dropdown').first().innerHTML().catch(() => 'N/A');
        out.push('dropdown html: ' + html.slice(0, 300));
        const bodyHtml = await page.locator('#enrollStudentModal').innerHTML().catch(() => 'N/A');
        out.push('modal html: ' + bodyHtml.slice(0, 800));
      }
    }
  }
} catch (e) { out.push('ERROR: ' + String(e).slice(0, 600)); }
console.log(out.join('\n'));
await browser.close();
