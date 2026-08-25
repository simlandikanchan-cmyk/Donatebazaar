import { chromium } from 'playwright';

(async () => {
  const browser = await chromium.launch();
  const page = await browser.newPage({ viewport: { width: 1440, height: 900 } });
  await page.goto('http://127.0.0.1:8000/', { waitUntil: 'networkidle' });
  await page.evaluate(() => window.scrollTo(0, document.body.scrollHeight));
  await page.waitForTimeout(1000);

  const result = await page.evaluate(() => {
    const anchor = document.querySelector('.social-btn');
    const svg = anchor.querySelector('svg');
    const hits = [];
    for (const sheet of document.styleSheets) {
      const href = sheet.href || 'inline';
      try {
        const walk = (rules) => {
          for (const rule of rules) {
            try {
              if (rule.selectorText && anchor.matches(rule.selectorText)) {
                hits.push({ sheet: href.split('/').pop(), sel: rule.selectorText.slice(0, 80), w: rule.style.width, h: rule.style.height, pad: rule.style.padding, mh: rule.style['min-height'], bg: rule.style.background, css: rule.style.cssText.slice(0, 300) });
              }
            } catch (e) {}
          }
        };
        walk(sheet.cssRules);
      } catch (e) {
        hits.push({ sheet: href.split('/').pop(), ERROR: String(e) });
      }
    }
    const cs = getComputedStyle(anchor);
    const csc = getComputedStyle(svg);
    return {
      anchorBox: {
        w: anchor.getBoundingClientRect().width,
        h: anchor.getBoundingClientRect().height,
        padding: cs.padding,
        minHeight: cs.minHeight,
        border: cs.border,
        widthCss: cs.width,
      },
      svgBox: { w: svg.getBoundingClientRect().width, h: svg.getBoundingClientRect().height, widthCss: csc.width, heightCss: csc.height },
      hits,
    };
  });
  console.log(JSON.stringify(result, null, 1));
  await browser.close();
})();
