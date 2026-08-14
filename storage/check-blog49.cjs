const { chromium } = require('playwright');

(async () => {
  const browser = await chromium.launch();
  const page = await browser.newPage({ viewport: { width: 1440, height: 1000 } });
  await page.goto('http://127.0.0.1:8000/login', { waitUntil: 'networkidle' });
  await page.fill('input[name=email]', 'admin@DonateBazaar.com');
  await page.fill('input[name=password]', 'admin@123');
  await Promise.all([
    page.waitForNavigation({ waitUntil: 'networkidle' }),
    page.click('button[type=submit]'),
  ]);
  await page.goto('http://127.0.0.1:8000/admin/blogs/49', { waitUntil: 'networkidle', timeout: 30000 });
  const info = await page.evaluate(() => {
    const errors = [];
    document.querySelectorAll('link[rel=stylesheet]').forEach((l) => errors.push('link: ' + l.href));
    return {
      title: document.title,
      pageTitle: (document.querySelector('.page-title, h1, .hero-title') || {}).textContent?.trim() || null,
      cssLinks: errors,
      bodyClasses: document.body.className,
    };
  });
  console.log(JSON.stringify(info, null, 1));
  await page.screenshot({ path: 'storage/blog49.png', fullPage: true });
  await browser.close();
})().catch((e) => { console.error('ERR', e.message); process.exit(1); });