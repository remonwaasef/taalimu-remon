import { newBrowser } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
const B = 'http://demo-center.localhost:8000';
try {
  await page.goto(B + '/login', { waitUntil: 'domcontentloaded', timeout: 30000 });
  await page.waitForTimeout(2000);
  await page.fill('input[name=email]', 'admin@demo.com');
  await page.fill('input[name=password]', 'NewStrongPass123!');
  await page.keyboard.press('Enter');
  await page.waitForTimeout(12000);
  console.log('login with new pw url=' + page.url());
  const html = await page.evaluate(() => document.body.innerText.slice(0, 200)).catch(() => 'nav');
  console.log('BODY: ' + (typeof html === 'string' ? html.replace(/\s+/g, ' ').slice(0, 120) : html));
} catch (e) { console.log('ERR: ' + e.message.slice(0, 200)); }
await browser.close();
