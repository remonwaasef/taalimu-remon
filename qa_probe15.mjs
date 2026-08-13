import { newBrowser, login, fetchAs, BASE } from './qa_helpers.mjs';
const { browser, ctx, page } = await newBrowser();
const results = [];
async function sweep(creds, label, paths) {
  await login(page, BASE, creds);
  await page.waitForTimeout(800);
  for (const p of paths) {
    const r = await fetchAs(page, BASE + p);
    results.push(`${label} ${p} -> ${r.status}`);
    await new Promise(res => setTimeout(res, 120));
  }
  await page.context().clearCookies();
}
await sweep({ email: 'instructor1@demo.com', password: 'password' }, 'INSTR', [
  '/instructor', '/instructor/settings', '/instructor/students-list', '/instructor/groups-list',
  '/instructor/schedules', '/instructor/attendance', '/instructor/online-classes',
  '/instructor/reports', '/instructor/reports/students', '/instructor/reports/payments',
  '/instructor/billing', '/instructor/whatsapp', '/instructor/students-create',
]);
await sweep({ email: 'parent@demo.com', password: 'password' }, 'PARENT', [
  '/parent', '/parent/courses', '/parent/attendance', '/parent/finances', '/parent/schedule', '/parent/profile',
]);
await sweep({ email: 'student1@demo.com', password: 'password' }, 'STUDENT', [
  '/campus', '/campus/courses', '/campus/attendance', '/campus/finances', '/campus/schedule', '/campus/profile',
]);
console.log(results.join('\n'));
await browser.close();