import { newBrowser, login, BASE } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
await login(page);
await page.goto(BASE + '/students/create');
await page.waitForTimeout(1200);
await page.fill('input[name="name"]', 'طالب فلاش اختبار ثلاثة');
await page.fill('input[name="phone"]', '01055552222');
await page.locator('#main_grade_select').selectOption({ index: 1 });
await page.click('.btn-next-step');
await page.waitForTimeout(400);
await page.locator('.course-checkbox-item').first().check();
await page.click('#btnSubmitStudent');
await page.waitForNavigation({ waitUntil: 'load', timeout: 20000 }).catch(() => {});
// Read IMMEDIATELY after load
const html = await page.locator('.flash-messages-container').innerHTML().catch(() => '(none)');
console.log('FLASH HTML immediately:', html.slice(0, 600));
// Also check body for the success message text
const body = await page.locator('body').innerText();
console.log('body has بنجاح:', body.includes('بنجاح'));
await browser.close();