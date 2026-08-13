import { newBrowser, login, BASE } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
const out = [];
try {
  await login(page, BASE);
  out.push('after login: ' + page.url());
  await page.goto(BASE + '/attendance');
  await page.waitForTimeout(2500);
  const body = await page.locator('body').innerText();
  out.push('attendance page title present: ' + /تحضير الطلاب|الحضور/.test(body));
  const sessionLinks = await page.locator('a[href*="/attendance/schedule/"]').count();
  out.push('schedule links: ' + sessionLinks);
  if (sessionLinks > 0) {
    const href = await page.locator('a[href*="/attendance/schedule/"]').first().getAttribute('href');
    out.push('first link: ' + href);
    await page.goto(href.startsWith('http') ? href : BASE + href);
    await page.waitForTimeout(2500);
    const body2 = await page.locator('body').innerText();
    out.push('show page has "قائمة الطلاب المسجلين": ' + body2.includes('قائمة الطلاب المسجلين'));
    out.push('show page snippet: ' + body2.slice(0, 400).replace(/\n+/g, ' | '));
    const presentButtons = await page.locator('button:has-text("حاضر")').count();
    out.push('present buttons: ' + presentButtons);
    if (presentButtons > 0) {
      const p = await page.locator('button:has-text("حاضر")').first();
      await p.click();
      await page.waitForTimeout(2000);
      const body3 = await page.locator('body').innerText();
      out.push('success msg after click: ' + /بنجاح/.test(body3));
      out.push('after click snippet: ' + body3.slice(0, 300).replace(/\n+/g, ' | '));
    }
  }
} catch (e) { out.push('ERROR: ' + String(e).slice(0, 500)); }
console.log(out.join('\n'));
await browser.close();
