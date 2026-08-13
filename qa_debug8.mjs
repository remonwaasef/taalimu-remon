import { newBrowser, login, BASE } from './qa_helpers.mjs';
const { browser, page } = await newBrowser();
await login(page);
for (let i = 0; i < 2; i++) {
  await page.goto(BASE + '/students');
  await page.waitForTimeout(1500);
  const finDebt = await page.locator('#finDebt').count();
  const finAll = await page.locator('#finAll').count();
  const stage = await page.locator('.stage-btn').count();
  const rows = await page.locator('.student-row').count();
  const title = await page.title().catch(() => '');
  console.log(`run${i} finDebt=${finDebt} finAll=${finAll} stage=${stage} rows=${rows} title=${title.slice(0,40)} url=${page.url()}`);
}
await browser.close();