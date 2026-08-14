const { chromium } = 'playwright';

(async () => {
  const browser = await chromium.launch();
  const page = await browser.newPage({ viewport: { width: 1440, height: 900 } });
  await page.goto('http://127.0.0.1:8000/', { waitUntil: 'networkidle' });
  await page.evaluate(() => window.scrollTo(0, document.body.scrollHeight));
  await page.waitForTimeout(1500);

  const targets = [
    '.footer-brand-mark svg',
    '.footer-brand-mark',
    '.footer-badge svg',
    '.social-btn svg',
    '.ftr-pill svg',
    '.cta-heart--1',
    '.cta-heart--2',
    '.cta-btn svg',
  ];

  for (const sel of targets) {
    const info = await page.evaluate((s) => {
      const el = document.querySelector(s);
      if (!el) return { sel: s, found: false };
      const r = el.getBoundingClientRect();
      const cs = getComputedStyle(el);
      const csParent = getComputedStyle(el.parentElement);
      return {
        sel: s,
        found: true,
        rect: { w: Math.round(r.width * 10) / 10, h: Math.round(r.height * 10) / 10 },
        display: cs.display,
        visibility: cs.visibility,
        opacity: cs.opacity,
        fill: cs.fill,
        color: cs.color,
        bg: csParent.backgroundColor,
        parentColor: csParent.color,
      };
    }, sel);
    console.log(JSON.stringify(info));
  }

  await browser.close();
})();

