import { chromium } from 'playwright';

const results = { steps: [], failedRequests: [], consoleErrors: [], responseCodes: {} };

(async () => {
  const browser = await chromium.launch();
  const page = await browser.newPage();

  page.on('response', (r) => {
    const code = r.status();
    results.responseCodes[r.url()] = code;
    if (code >= 500) {
      results.failedRequests.push({ status: code, url: r.url() });
    }
  });
  page.on('console', (msg) => {
    if (msg.type() === 'error') results.consoleErrors.push(msg.text());
  });
  page.on('pageerror', (err) => {
    results.consoleErrors.push('PAGEERROR: ' + err.message);
  });

  try {
    await page.goto('http://localhost:8000/admin/login', { waitUntil: 'networkidle', timeout: 30000 });
    results.steps.push('login page loaded: ' + page.url());
    await page.fill('input[name="email"]', 'admin@admin.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForTimeout(8000);
    results.steps.push('after submit url: ' + page.url());
    const bodyText = (await page.evaluate(() => document.body ? document.body.innerText.slice(0, 700) : 'NO BODY')).replace(/\n+/g, ' | ');
    results.steps.push('body preview: ' + bodyText);
  } catch (e) {
    results.steps.push('EXCEPTION: ' + e.message);
  }

  
  await browser.close();
  console.log(JSON.stringify(results, null, 2));
})();

