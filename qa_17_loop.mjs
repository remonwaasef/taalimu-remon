import { newBrowser } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
const B = 'http://demo-center.localhost:8000';
try {
  await page.goto(B + '/login', { waitUntil: 'domcontentloaded', timeout: 30000 });
  await page.waitForTimeout(2000);
  await page.fill('input[name=email]', 'admin@demo.com');
  await page.fill('input[name=password]', 'password');
  await page.keyboard.press('Enter');
  await page.waitForTimeout(12000);
  await page.waitForSelector('input[name=current_password]', { timeout: 20000 });
  const pw = 'NewStrongPass123!';
  await page.fill('input[name=current_password]', 'password');
  await page.fill('input[name=password]', pw);
  await page.fill('input[name=password_confirmation]', pw);
  await page.keyboard.press('Enter');
  await page.waitForTimeout(10000);
  console.log('url2=' + page.url());
  const html = await page.evaluate(() => document.body.innerText.slice(0, 300)).catch(() => 'nav');
  console.log('BODY: ' + (typeof html === 'string' ? html.replace(/\s+/g, ' ').slice(0, 200) : html));
} catch (e) { console.log('ERR: ' + e.message.slice(0, 200)); }
await browser.close();
