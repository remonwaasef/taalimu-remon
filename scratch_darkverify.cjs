const { chromium } = require('playwright');

const BASE = process.argv[2] || 'https://demo-center.taalimu.com';

function lum(rgb) {
  const m = rgb.match(/rgba?\((\d+),\s*(\d+),\s*(\d+)/);
  if (!m) return null;
  const [r, g, b] = [+m[1], +m[2], +m[3]];
  return 0.299 * r + 0.587 * g + 0.114 * b;
}

(async () => {
  const browser = await chromium.launch();
  const page = await browser.newPage({ viewport: { width: 1440, height: 900 } });

  await page.goto(BASE + '/login', { waitUntil: 'networkidle' }).catch(() => {});
  await page.evaluate(() => {
    document.documentElement.classList.add('dark');
    localStorage.setItem('theme', 'dark');
  });
  await page.goto(BASE + '/login', { waitUntil: 'networkidle' }).catch(() => {});
  await page.fill('#email', 'admin@demo.com').catch(() => {});
  await page.fill('#password', 'password').catch(() => {});
  await page.click('button[type="submit"]').catch(() => {});
  await page.waitForTimeout(4000);
  await page.goto(BASE + '/students/create', { waitUntil: 'networkidle' }).catch(e => console.log('nav err', e.message));
  await page.waitForTimeout(1500);

  const darkTexts = await page.evaluate(() => {
    const out = [];
    const skipTags = ['SCRIPT', 'STYLE', 'NOSCRIPT'];
    const walker = document.createTreeWalker(document.body, NodeFilter.SHOW_ELEMENT);
    let n;
    while ((n = walker.nextNode())) {
      if (skipTags.includes(n.tagName)) continue;
      if (['svg', 'path', 'i'].includes(n.tagName.toLowerCase())) continue;
      if (n.closest('.bg-primary, .btn-primary, .btn-success, .btn-danger, .btn-warning, .btn-info, .btn-dark')) continue;
      if (n.closest('table, .table, .modal')) continue;
      const txt = (n.textContent || '').trim();
      if (txt.length < 2 || n.children.length && n.children.length > 3) continue;
      const st = getComputedStyle(n);
      if (st.display === 'none' || st.visibility === 'hidden' || +st.opacity === 0) continue;
      const rgb = st.color;
      const l = 0.299 * +rgb.match(/\d+/g)[0] + 0.587 * +rgb.match(/\d+/g)[1] + 0.114 * +rgb.match(/\d+/g)[2];
      if (l < 110) out.push({ tag: n.tagName, cls: (n.className || '').toString().slice(0, 70), txt: txt.slice(0, 60), color: st.color });
    }
    return out;
  });

  console.log('=== DARK TEXTS FOUND:', darkTexts.length, '===');
  darkTexts.slice(0, 40).forEach(d => console.log(`${d.tag} .${d.cls} color=${d.color} => "${d.txt}"`));

  const labels = await page.evaluate(() => {
    const out = [];
    document.querySelectorAll('label').forEach(l => out.push({ txt: l.textContent.trim().slice(0, 50), color: getComputedStyle(l).color }));
    return out;
  });
  console.log('=== LABELS ===');
  labels.slice(0, 12).forEach(l => console.log(` - color=${l.color} => "${l.txt}"`));

  await browser.close();
})();