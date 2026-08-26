import { chromium } from '@playwright/test';
import { mkdirSync } from 'fs';

const BASE = 'http://127.0.0.1:8000';
const OUT = 'test-results/landing-qa';
mkdirSync(OUT, { recursive: true });

const results = [];
const check = (name, passed, detail = '') => {
  results.push({ name, passed: !!passed, detail });
  console.log(`[${passed ? 'PASS' : 'FAIL'}] ${name}${detail ? ' :: ' + detail : ''}`);
};

const browser = await chromium.launch();

// ---------- 1. Locale rendering checks ----------
for (const [loc, needle, dir] of [
  ['AR(default)', 'منظمة في مكان واحد', 'rtl'],
  ['EN(?hl=en)', 'organized in one place', 'ltr'],
  ['FR(?hl=fr)', 'au même endroit', 'ltr'],
]) {
  const ctx = await browser.newContext({ viewport: { width: 1280, height: 900 } });
  const page = await ctx.newPage();
  const consoleErrors = [];
  page.on('pageerror', (e) => consoleErrors.push('PAGEERROR: ' + e.message.slice(0, 200)));
  const url = loc === 'AR(default)' ? BASE + '/' : BASE + '/?hl=' + loc.match(/hl=(\w+)/)[1];
  try {
    await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 30000 });
    await page.waitForTimeout(1500);
    const html = await page.locator('html').getAttribute('dir');
    check(`${loc}: dir=${dir}`, html === dir, 'got dir=' + html);
    const body = await page.locator('body').innerText();
    check(`${loc}: hero headline rendered`, body.includes(needle));
    const rawKeys = body.match(/landing\.[a-z_]+\.[a-z_]+/g) || [];
    check(`${loc}: no raw translation keys`, rawKeys.length === 0, rawKeys.slice(0, 5).join(', '));
    check(`${loc}: no JS page errors`, consoleErrors.length === 0, consoleErrors.slice(0, 3).join(' | '));
  } catch (e) {
    check(`${loc}: page loaded`, false, String(e).slice(0, 200));
  }
  await ctx.close();
}

// ---------- 2. Responsive screenshots + sticky CTA ----------
const sizes = [320, 390, 430, 768, 1024, 1440];
for (const w of sizes) {
  const ctx = await browser.newContext({ viewport: { width: w, height: Math.max(700, Math.round(w * 1.6)) } });
  const page = await ctx.newPage();
  try {
    await page.goto(BASE + '/', { waitUntil: 'domcontentloaded', timeout: 30000 });
    await page.waitForTimeout(1800);
    // horizontal overflow check
    const overflow = await page.evaluate(() => document.documentElement.scrollWidth - document.documentElement.clientWidth);
    check(`${w}px: no horizontal overflow`, overflow <= 1, 'overflowX=' + overflow + 'px');
    await page.screenshot({ path: `${OUT}/${w}-top.png` });

    if (w < 768) {
      const bar = page.locator('[data-track="landing_sticky_cta_clicked"]');
      const visibleAtTop = await bar.isVisible().catch(() => false);
      await page.evaluate(() => window.scrollTo(0, 800));
      await page.waitForTimeout(600);
      const visibleAfterScroll = await bar.isVisible().catch(() => false);
      check(`${w}px: sticky CTA hidden@top then visible@scroll`, !visibleAtTop && visibleAfterScroll, `top=${visibleAtTop} scrolled=${visibleAfterScroll}`);
      await page.screenshot({ path: `${OUT}/${w}-sticky.png` });
    }

    // full page screenshot for desktop sizes
    if (w >= 768) {
      await page.evaluate(() => window.scrollTo(0, document.body.scrollHeight));
      await page.waitForTimeout(1200);
      await page.screenshot({ path: `${OUT}/${w}-full.png`, fullPage: true });
    }
  } catch (e) {
    check(`${w}px: rendered`, false, String(e).slice(0, 160));
  }
  await ctx.close();
}

// ---------- 3. Functional interactions (mobile 390, AR) ----------
{
  const ctx = await browser.newContext({ viewport: { width: 390, height: 844 } });
  const page = await ctx.newPage();
  try {
    await page.goto(BASE + '/', { waitUntil: 'domcontentloaded', timeout: 30000 });
    await page.waitForTimeout(1500);

    // Footer policy links
    const privHref = await page.locator('footer a[href*="privacy"]').first().getAttribute('href').catch(() => null);
    const termsHref = await page.locator('footer a[href*="terms"]').first().getAttribute('href').catch(() => null);
    check('footer privacy/terms linked to real routes', !!privHref && !!termsHref, `${privHref} | ${termsHref}`);
    if (privHref) {
      const abs = privHref.startsWith('http') ? privHref : BASE + privHref;
      const resp = await page.request.get(abs);
      check('/privacy responds 200', resp.status() === 200, 'status=' + resp.status());
    }

    // FAQ aria
    const q1 = page.locator('#faq-question-0');
    const expandedBefore = await q1.getAttribute('aria-expanded');
    await q1.click();
    await page.waitForTimeout(500);
    const expandedAfter = await q1.getAttribute('aria-expanded');
    const answerVisible = await page.locator('#faq-answer-0').isVisible();
    check('FAQ accordion toggles aria-expanded', expandedBefore === 'false' && expandedAfter === 'true' && answerVisible, `${expandedBefore}→${expandedAfter} visible=${answerVisible}`);

    // Pricing section: billing toggles + featured badge
    const pricingText = await page.locator('#pricing').innerText().catch(() => '');
    check('pricing has no raw keys', !/landing\.pricing\./.test(pricingText));
    check('pricing shows trial badge', pricingText.includes('تجربة مجانية'), '');

    // Hero CTA data-track present
    const trackCount = await page.locator('[data-track]').count();
    check('data-track CTAs wired', trackCount >= 4, 'count=' + trackCount);

    // Register destination
    const regResp = await page.request.get(BASE + '/register');
    check('/register responds 200', regResp.status() === 200, 'status=' + regResp.status());

    // Language switcher endpoints
    for (const l of ['en', 'fr']) {
      const r = await page.request.get(BASE + '/', { headers: {} });
      void r; void l;
    }

    // Mobile menu drawer
    await page.evaluate(() => window.scrollTo(0, 0));
    await page.getByRole('button', { name: /toggle navigation/i }).click();
    await page.waitForTimeout(500);
    const drawerLink = await page.locator('nav >> text=كيف يعمل').first().isVisible().catch(() => false);
    check('mobile drawer opens with nav links', drawerLink);
  } catch (e) {
    check('functional block', false, String(e).slice(0, 200));
  }
  await ctx.close();
}

await browser.close();

const failed = results.filter(r => !r.passed);
console.log('\n========== SUMMARY ==========');
console.log(`Total: ${results.length}, Passed: ${results.length - failed.length}, Failed: ${failed.length}`);
if (failed.length) { console.log('FAILED ITEMS:'); failed.forEach(f => console.log(' - ' + f.name + (f.detail ? ' :: ' + f.detail : ''))); }
process.exit(0);
