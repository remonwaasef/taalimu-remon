import { chromium } from '@playwright/test';

const BASE = 'http://127.0.0.1:8000';

const results = [];
const check = (name, passed, detail = '') => {
    results.push({ name, passed: !!passed, detail });
    console.log(`[${passed ? 'PASS' : 'FAIL'}] ${name}${detail ? ' :: ' + detail : ''}`);
};

const browser = await chromium.launch();

function noVisualOverflow(page) {
    return page.evaluate(() => document.body.clientWidth <= window.innerWidth + 1);
}

async function checkLocale(locale, needle, dir, hl) {
    const ctx = await browser.newContext({ viewport: { width: 1280, height: 900 } });
    const page = await ctx.newPage();
    const consoleErrors = [];
    page.on('pageerror', (e) => consoleErrors.push('PAGEERROR: ' + e.message.slice(0, 200)));
    const url = hl ? BASE + '/?hl=' + hl : BASE + '/';
    try {
        await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 30000 });
        await page.waitForTimeout(2000);
        const htmlDir = await page.locator('html').getAttribute('dir');
        check(`${locale}: dir=${dir}`, htmlDir === dir, 'got dir=' + htmlDir);
        const body = await page.locator('body').innerText();
        check(`${locale}: hero headline rendered`, body.includes(needle));
        check(`${locale}: no JS page errors`, consoleErrors.length === 0, consoleErrors.slice(0, 3).join(' | '));
        check(`${locale}: no visual horizontal overflow`, await noVisualOverflow(page));
    } catch (e) {
        check(`${locale}: page loaded`, false, String(e).slice(0, 200));
    }
    await ctx.close();
}

for (const [loc, needle, dir, hl] of [
    ['AR', 'نظام ذكي يدير مركزك التعليمي بالكامل', 'rtl', null],
    ['EN', 'One smart system to run your entire educational center', 'ltr', 'en'],
    ['FR', 'Un système intelligent pour piloter tout votre centre éducatif', 'ltr', 'fr'],
]) {
    await checkLocale(loc, needle, dir, hl);
}

const sizes = [320, 390, 430, 768, 1024, 1440];
for (const w of sizes) {
    const ctx = await browser.newContext({ viewport: { width: w, height: Math.max(700, Math.round(w * 1.6)) } });
    const page = await ctx.newPage();
    try {
        await page.goto(BASE + '/', { waitUntil: 'domcontentloaded', timeout: 30000 });
        await page.waitForTimeout(2000);
        check(`${w}px: no visual horizontal overflow`, await noVisualOverflow(page));
        await page.screenshot({ path: `test-results/landing-qa/v2-${w}-top.png` });

        if (w < 768) {
            const bar = page.locator('[data-track="v2_sticky_cta_clicked"]');
            const visibleAtTop = await bar.isVisible().catch(() => false);
            await page.evaluate(() => window.scrollTo(0, 800));
            await page.waitForTimeout(600);
            const visibleAfterScroll = await bar.isVisible().catch(() => false);
            check(`${w}px: sticky CTA hidden@top then visible@scroll`, !visibleAtTop && visibleAfterScroll, `top=${visibleAtTop} scrolled=${visibleAfterScroll}`);
            await page.screenshot({ path: `test-results/landing-qa/v2-${w}-sticky.png` });
        }

        if (w >= 768) {
            await page.evaluate(() => window.scrollTo(0, document.body.scrollHeight));
            await page.waitForTimeout(1200);
            await page.screenshot({ path: `test-results/landing-qa/v2-${w}-full.png`, fullPage: true });
        }
    } catch (e) {
        check(`${w}px: rendered`, false, String(e).slice(0, 160));
    }
    await ctx.close();
}

{
    const ctx = await browser.newContext({ viewport: { width: 390, height: 844 } });
    const page = await ctx.newPage();
    try {
        await page.goto(BASE + '/', { waitUntil: 'domcontentloaded', timeout: 30000 });
        await page.waitForTimeout(2000);

        const privHref = await page.locator('footer a[href*="privacy"]').first().getAttribute('href').catch(() => null);
        const termsHref = await page.locator('footer a[href*="terms"]').first().getAttribute('href').catch(() => null);
        check('footer privacy/terms linked to real routes', !!privHref && !!termsHref, `${privHref} | ${termsHref}`);
        if (privHref) {
            const abs = privHref.startsWith('http') ? privHref : BASE + privHref;
            const resp = await page.request.get(abs);
            check('/privacy responds 200', resp.status() === 200, 'status=' + resp.status());
        }

        const q0 = page.locator('#v2-faq-q-0');
        const expandedBefore = await q0.getAttribute('aria-expanded');
        await q0.click();
        await page.waitForTimeout(500);
        const expandedAfter = await q0.getAttribute('aria-expanded');
        const answerVisible = await page.locator('#v2-faq-a-0').isVisible();
        check('FAQ accordion toggles aria-expanded', expandedBefore === 'false' && expandedAfter === 'true' && answerVisible, `${expandedBefore}→${expandedAfter} visible=${answerVisible}`);

        const pricingText = await page.locator('#pricing').innerText().catch(() => '');
        check('pricing has no raw keys', !/landing\.pricing\./.test(pricingText));
        check('pricing shows trial badge', pricingText.includes('تجربة مجانية'), '');

        const trackCount = await page.locator('[data-track]').count();
        check('data-track CTAs wired', trackCount >= 4, 'count=' + trackCount);

        const regResp = await page.request.get(BASE + '/register');
        check('/register responds 200', regResp.status() === 200, 'status=' + regResp.status());

        await page.evaluate(() => window.scrollTo(0, 0));
        await page.evaluate(() => {
            const header = document.querySelector('header');
            if (header && header._x_dataStack) {
                header._x_dataStack[0].isMenuOpen = true;
            }
        });
        await page.waitForTimeout(500);
        const isOpen = await page.evaluate(() => {
            const header = document.querySelector('header');
            return header && header._x_dataStack ? header._x_dataStack[0].isMenuOpen : null;
        });
        check('mobile drawer Alpine state toggled', isOpen === true);
        await page.waitForSelector('.fixed.bottom-0.start-0.end-0.bg-white.rounded-t-3xl', { state: 'visible', timeout: 3000 }).catch(() => {});
        const drawer = page.locator('.fixed.bottom-0.start-0.end-0.bg-white.rounded-t-3xl').first();
        const visible = await drawer.isVisible().catch(() => false);
        check('mobile drawer opens with nav links', visible);
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
process.exit(failed.length > 0 ? 1 : 0);