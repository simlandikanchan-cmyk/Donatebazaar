const { chromium } = require('playwright');
const fs = require('fs');

const BASE = 'http://127.0.0.1:8000';
const WIDTH = 350;

// admin GET routes (uri) collected from `php artisan route:list --path=admin --method=GET`
// plus sample IDs resolved from the database.
const ids = {
  campaign: 57, blog: 34, event: 19, coupon: 1, category: 1, giftCard: 1,
  donation: 89, jobPost: 1, volunteer: 3, partnership: 11, legal: 1,
  fundraiserLevel: 1, contacts: 1, categoryProduct: 1, campaignProduct: 1,
  volunteerApplication: 3,
};

const routes = [
  '/admin/dashboard',
  '/admin/dashboard/campaigns',
  '/admin/campaign',
  '/admin/campaign/' + ids.campaign,
  '/admin/campaign/' + ids.campaign + '/edit',
  '/admin/campaign/' + ids.campaign + '/quick',
  '/admin/campaign-products',
  '/admin/category-products',
  '/admin/categories',
  '/admin/categories/create',
  '/admin/categories/' + ids.category,
  '/admin/categories/' + ids.category + '/edit',
  '/admin/partnerships',
  '/admin/partnerships/export',
  '/admin/partnerships/' + ids.partnership,
  '/admin/messages',
  '/admin/messages/' + ids.contacts,
  '/admin/blogs',
  '/admin/blogs/pending',
  '/admin/blogs/flagged',
  '/admin/blogs/analytics',
  '/admin/blogs/carousel',
  '/admin/blogs/create',
  '/admin/blogs/' + ids.blog,
  '/admin/blogs/' + ids.blog + '/edit',
  '/admin/applications',
  '/admin/applications/' + 1,
  '/admin/organizations',
  '/admin/organizations/create',
  '/admin/donations',
  '/admin/donations/' + ids.donation,
  '/admin/events',
  '/admin/events/create',
  '/admin/events/' + ids.event,
  '/admin/events/' + ids.event + '/edit',
  '/admin/volunteers',
  '/admin/volunteers/' + ids.volunteer,
  '/admin/volunteer_applications',
  '/admin/volunteer_applications/' + ids.volunteerApplication,
  '/admin/volunteer_assignments',
  '/admin/volunteer_assignments/create',
  '/admin/coupons',
  '/admin/coupons/create',
  '/admin/coupons/' + ids.coupon + '/edit',
  '/admin/job_posts',
  '/admin/job_posts/create',
  '/admin/job_posts/' + ids.jobPost,
  '/admin/job_posts/' + ids.jobPost + '/edit',
  '/admin/job_post_applications',
  '/admin/gift-cards',
  '/admin/gift-cards/' + ids.giftCard,
  '/admin/faqs',
  '/admin/faqs/create',
  '/admin/legal',
  '/admin/legal/' + ids.legal + '/edit',
  '/admin/subscribers',
  '/admin/success-stories',
  '/admin/fundraiser-levels',
  '/admin/fundraiser-levels/create',
  '/admin/fundraiser-levels/' + ids.fundraiserLevel,
  '/admin/profile',
  '/admin/contacts',
  '/admin/contacts/delete/' + ids.contacts,
];

function classifyOverflow(scrollW) {
  const diff = scrollW - WIDTH;
  if (diff <= 1) return 'ok';
  if (diff <= 8) return 'minor';
  return 'overflow';
}

(async () => {
  const browser = await chromium.launch();
  const context = await browser.newContext({ viewport: { width: WIDTH, height: 800 } });
  const page = await context.newPage();

  const consoleMsgs = [];
  page.on('console', m => { if (m.type() === 'error' || m.type() === 'warning') consoleMsgs.push(m.type() + ': ' + m.text()); });
  page.on('pageerror', e => consoleMsgs.push('pageerror: ' + e.message));

  // ---- Login ----
  await page.goto(BASE + '/login', { waitUntil: 'networkidle' });
  // Try common field names
  const emailSel = 'input[type="email"], input[name="email"]';
  const passSel = 'input[type="password"], input[name="password"]';
  await page.fill(emailSel, 'admin@donatebazar.com');
  await page.fill(passSel, 'password123');
  await page.click('button[type="submit"]');
  await page.waitForTimeout(1500);

  const afterLoginUrl = page.url();
  const bodyText = await page.evaluate(() => document.body.innerText.slice(0, 200));
  if (afterLoginUrl.includes('/login')) {
    console.error('LOGIN FAILED — still at ' + afterLoginUrl + '. Body: ' + bodyText);
    await browser.close();
    process.exit(2);
  }

  const results = [];
  for (const route of routes) {
    consoleMsgs.length = 0;
    let info = { route, status: 'ok', overflow: 'ok', scrollW: WIDTH, diff: 0, console: [], error: null };
    try {
      const resp = await page.goto(BASE + route, { waitUntil: 'networkidle', timeout: 20000 });
      info.status = resp ? resp.status() : 'no-response';
      // measure overflow on documentElement and body
      const m = await page.evaluate(() => {
        const de = document.documentElement;
        const b = document.body;
        // True page-level horizontal overflow = documentElement.scrollWidth.
        // Find the widest element that is NOT inside a horizontal scroll container
        // (so internal table scrollers don't count as page overflow).
        let maxRight = 0, offender = '';
        const inScroller = (el) => {
          let p = el.parentElement;
          while (p) {
            const cs = getComputedStyle(p);
            if ((cs.overflowX === 'auto' || cs.overflowX === 'scroll') && p.scrollWidth > p.clientWidth + 1) return true;
            p = p.parentElement;
          }
          return false;
        };
        const walk = (el) => {
          const r = el.getBoundingClientRect();
          if (r.right > maxRight && r.width > 0 && !inScroller(el)) {
            maxRight = r.right;
            offender = (el.tagName + (el.className && typeof el.className === 'string' ? '.' + el.className.split(' ').join('.') : ''));
          }
          for (const c of el.children) walk(c);
        };
        walk(de);
        return { deScroll: de.scrollWidth, bScroll: b.scrollWidth, maxRight, offender };
      });
      info.scrollW = m.deScroll;
      info.bodyScrollW = m.bScroll;
      info.diff = info.scrollW - WIDTH;
      info.overflow = classifyOverflow(info.scrollW);
      info.offender = m.maxRight > WIDTH + 1 ? m.offender : null;
      info.console = consoleMsgs.slice();
    } catch (e) {
      info.error = String(e.message || e).slice(0, 300);
      info.status = 'exception';
    }
    results.push(info);
    console.log(`[${info.overflow.toUpperCase()}] ${info.status} ${route}  scrollW=${info.scrollW} clipped=${info.bodyScrollW>WIDTH+1?('yes('+info.bodyScrollW+'/'+info.offender+')'):'no'}${info.error ? ' ERR=' + info.error : ''}`);
  }

  await browser.close();
  fs.writeFileSync('C:\\Users\\stdlocal\\AppData\\Local\\Temp\\kilo\\admin_350_report.json', JSON.stringify({ loginUrl: afterLoginUrl, loginBody: bodyText, results }, null, 2));
  console.log('\nLOGIN landed at: ' + afterLoginUrl);
  console.log('Wrote report -> admin_350_report.json');
})().catch(e => { console.error('FATAL', e); process.exit(1); });
