# Instructions

- Following Playwright test failed.
- Explain why, be concise, respect Playwright best practices.
- Provide a snippet of code with the fix, if possible.

# Test info

- Name: comprehensive-verification.spec.ts >> Comprehensive Independent Verification >> Financial Flow Verification >> donor can view campaign 98
- Location: tests\browser\comprehensive-verification.spec.ts:211:5

# Error details

```
Error: expect(received).toBe(expected) // Object.is equality

Expected: 200
Received: 403
```

# Page snapshot

```yaml
- generic [active] [ref=f2e1]:
  - banner [ref=f2e2]:
    - generic [ref=f2e3]:
      - link "Laravel — Go to homepage" [ref=f2e4] [cursor=pointer]:
        - /url: http://127.0.0.1:8000
        - generic [ref=f2e8]: DonateBazaar
      - navigation "Primary navigation" [ref=f2e9]:
        - link "Home" [ref=f2e10] [cursor=pointer]:
          - /url: http://127.0.0.1:8000
        - link "Campaigns" [ref=f2e11] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/all-campaigns
        - button "About" [ref=f2e12] [cursor=pointer]
        - link "Contact" [ref=f2e15] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/contact
      - generic [ref=f2e16]:
        - link "Search" [ref=f2e17] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/search
        - link "Start Fundraise" [ref=f2e22] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/campaign/create
        - button "Notifications" [ref=f2e26] [cursor=pointer]:
          - generic [ref=f2e27]: "0"
        - button "Account menu for QA Donor" [ref=f2e33] [cursor=pointer]:
          - img "QA Donor" [ref=f2e34]
  - main [ref=f2e37]:
    - generic [ref=f2e40]:
      - generic [ref=f2e45]: Forbidden
      - heading "You don't have permission" [level=1] [ref=f2e46]
      - paragraph [ref=f2e47]: This area is restricted. If you believe this is an error, please contact the site administrator.
      - generic [ref=f2e48]:
        - link "Back to Home" [ref=f2e49] [cursor=pointer]:
          - /url: /
        - button "Go Back" [ref=f2e54] [cursor=pointer]
  - contentinfo [ref=f2e58]:
    - generic [ref=f2e59]:
      - generic [ref=f2e60]:
        - generic [ref=f2e61]:
          - generic [ref=f2e62]: Live campaigns
          - generic [ref=f2e64]:
            - heading "Ready to Make an Impact?" [level=2] [ref=f2e65]
            - paragraph [ref=f2e66]: Join thousands of donors changing lives every single day.
          - generic [ref=f2e67]:
            - generic [ref=f2e68]:
              - generic [ref=f2e69]: "0"
              - generic [ref=f2e70]: Donors
            - generic [ref=f2e71]:
              - generic [ref=f2e72]: ₹0
              - generic [ref=f2e73]: Raised
            - generic [ref=f2e74]:
              - generic [ref=f2e75]: "0"
              - generic [ref=f2e76]: Campaigns
        - generic [ref=f2e77]:
          - link "Explore Campaigns" [ref=f2e78] [cursor=pointer]:
            - /url: http://127.0.0.1:8000/all-campaigns
          - link "Start a Fundraiser →" [ref=f2e82] [cursor=pointer]:
            - /url: http://127.0.0.1:8000/campaign/create
            - generic [ref=f2e83]:
              - text: Start a Fundraiser
              - generic [ref=f2e84]: →
      - generic [ref=f2e85]:
        - generic [ref=f2e86]:
          - generic [ref=f2e87]: DonateBazaar
          - paragraph [ref=f2e88]: A trusted platform connecting donors with verified causes. Transparent, secure, and impactful giving for a better world.
          - generic [ref=f2e89]: 2.5M+ donors trust us
          - generic [ref=f2e91]:
            - link [ref=f2e92] [cursor=pointer]:
              - /url: https://www.facebook.com/
            - link [ref=f2e96] [cursor=pointer]:
              - /url: https://x.com/
            - link [ref=f2e100] [cursor=pointer]:
              - /url: https://www.instagram.com/
            - link [ref=f2e105] [cursor=pointer]:
              - /url: https://www.linkedin.com/
            - link [ref=f2e111] [cursor=pointer]:
              - /url: https://www.youtube.com/
        - generic [ref=f2e116]:
          - heading "Platform" [level=3] [ref=f2e117]
          - list [ref=f2e118]:
            - listitem [ref=f2e119]:
              - link "Home" [ref=f2e120] [cursor=pointer]:
                - /url: http://127.0.0.1:8000
            - listitem [ref=f2e121]:
              - link "Campaigns" [ref=f2e122] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/all-campaigns
            - listitem [ref=f2e123]:
              - link "Start Fundraiser" [ref=f2e124] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/campaign/create
            - listitem [ref=f2e125]:
              - link "Search" [ref=f2e126] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/search
            - listitem [ref=f2e127]:
              - link "Impact Stories" [ref=f2e128] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/impact
            - listitem [ref=f2e129]:
              - link "How It Works" [ref=f2e130] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/how-it-works
            - listitem [ref=f2e131]:
              - link "Disaster Relief" [ref=f2e132] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/disaster-relief
        - generic [ref=f2e133]:
          - heading "Company" [level=3] [ref=f2e134]
          - list [ref=f2e135]:
            - listitem [ref=f2e136]:
              - link "About Us" [ref=f2e137] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/about
            - listitem [ref=f2e138]:
              - link "Contact" [ref=f2e139] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/contact
            - listitem [ref=f2e140]:
              - link "Careers" [ref=f2e141] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/career
            - listitem [ref=f2e142]:
              - link "Blog" [ref=f2e143] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/blog
            - listitem [ref=f2e144]:
              - link "Partnership" [ref=f2e145] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/partnership
        - generic [ref=f2e146]:
          - heading "Stay Updated" [level=3] [ref=f2e147]
          - paragraph [ref=f2e148]: Get inspiring stories, new campaigns, and impact reports — straight to your inbox.
          - generic [ref=f2e149]:
            - generic [ref=f2e150]: Your email
            - generic [ref=f2e152]:
              - textbox "Email for newsletter" [ref=f2e153]:
                - /placeholder: Your email
              - button "Subscribe" [ref=f2e154] [cursor=pointer]
          - generic [ref=f2e156]:
            - generic [ref=f2e157]: 10K+ subscribers
            - generic [ref=f2e159]: No spam, ever
      - generic [ref=f2e162]:
        - generic [ref=f2e163]: © 2026 DonateBazaar. All rights reserved.
        - generic [ref=f2e164]:
          - text: Made with
          - generic [ref=f2e165]: ♥
          - text: for a better world
        - generic [ref=f2e166]:
          - generic [ref=f2e167]:
            - link "Privacy" [ref=f2e168] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/privacy-policy
            - link "Terms" [ref=f2e169] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/terms-of-service
            - link "Refunds" [ref=f2e170] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/refund-cancellation
            - link "Cookies" [ref=f2e171] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/cookie-policy
            - link "FAQ" [ref=f2e172] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/faq
          - button "Back to top" [ref=f2e173] [cursor=pointer]
  - button "Open chat" [ref=f2e178] [cursor=pointer]:
    - generic [ref=f2e179]: 
```

# Test source

```ts
  114 |   test.describe('Dashboard Inner Pages - Donor', () => {
  115 |     test('donor dashboard loads', async ({ page }) => {
  116 |       await loginAsDonor(page);
  117 |       const response = await page.goto('/user/dashboard');
  118 |       expect(response?.status()).toBe(200);
  119 |       await expect(page.locator('body')).toBeVisible();
  120 |     });
  121 | 
  122 |     test('donor wallet page loads', async ({ page }) => {
  123 |       await loginAsDonor(page);
  124 |       const response = await page.goto('/user/dashboard/wallet');
  125 |       expect(response?.status()).toBe(200);
  126 |       await expect(page.locator('body')).toBeVisible();
  127 |     });
  128 | 
  129 |     test('donor donations page loads', async ({ page }) => {
  130 |       await loginAsDonor(page);
  131 |       const response = await page.goto('/user/dashboard/donations');
  132 |       expect(response?.status()).toBe(200);
  133 |       await expect(page.locator('body')).toBeVisible();
  134 |     });
  135 | 
  136 |     test('donor saved campaigns loads', async ({ page }) => {
  137 |       await loginAsDonor(page);
  138 |       const response = await page.goto('/user/dashboard/saved-campaigns');
  139 |       expect(response?.status()).toBe(200);
  140 |       await expect(page.locator('body')).toBeVisible();
  141 |     });
  142 |   });
  143 | 
  144 |   test.describe('Dashboard Inner Pages - Admin', () => {
  145 |     test('admin dashboard loads', async ({ page }) => {
  146 |       await loginAsAdmin(page);
  147 |       const response = await page.goto('/admin/dashboard');
  148 |       expect(response?.status()).toBe(200);
  149 |       await expect(page.locator('body')).toBeVisible();
  150 |     });
  151 | 
  152 |     test('admin campaigns page loads', async ({ page }) => {
  153 |       await loginAsAdmin(page);
  154 |       const response = await page.goto('/admin/campaign');
  155 |       expect(response?.status()).toBe(200);
  156 |       await expect(page.locator('body')).toBeVisible();
  157 |     });
  158 | 
  159 |     test('admin applications page loads', async ({ page }) => {
  160 |       await loginAsAdmin(page);
  161 |       const response = await page.goto('/admin/applications');
  162 |       expect(response?.status()).toBe(200);
  163 |       await expect(page.locator('body')).toBeVisible();
  164 |     });
  165 | 
  166 |     test('admin blogs page loads', async ({ page }) => {
  167 |       await loginAsAdmin(page);
  168 |       const response = await page.goto('/admin/blogs');
  169 |       expect(response?.status()).toBe(200);
  170 |       await expect(page.locator('body')).toBeVisible();
  171 |     });
  172 |   });
  173 | 
  174 |   test.describe('Authorization / IDOR', () => {
  175 |     test('unauthenticated user redirected to login', async ({ page }) => {
  176 |       const response = await page.goto('/user/dashboard');
  177 |       expect(response?.status()).toBe(302);
  178 |       await page.waitForTimeout(1000);
  179 |       expect(page.url()).toContain('/login');
  180 |     });
  181 | 
  182 |     test('donor cannot access admin dashboard', async ({ page }) => {
  183 |       await loginAsDonor(page);
  184 |       const response = await page.goto('/admin/dashboard');
  185 |       expect(response?.status()).toBe(302);
  186 |     });
  187 | 
  188 |     test('creator cannot access admin dashboard', async ({ page }) => {
  189 |       await loginAsCreator(page);
  190 |       const response = await page.goto('/admin/dashboard');
  191 |       expect(response?.status()).toBe(302);
  192 |     });
  193 | 
  194 |     test('unauthenticated admin redirected to login', async ({ page }) => {
  195 |       const response = await page.goto('/admin/dashboard');
  196 |       expect(response?.status()).toBe(302);
  197 |       expect(page.url()).toContain('/login');
  198 |     });
  199 |   });
  200 | 
  201 |   test.describe('Financial Flow Verification', () => {
  202 |     test('campaign 98 exists and is paused', async ({ page }) => {
  203 |       await loginAsCreator(page);
  204 |       const response = await page.goto('/campaign/98');
  205 |       expect(response?.status()).toBe(200);
  206 |       await expect(page.locator('body')).toBeVisible();
  207 |       const text = await page.textContent('body');
  208 |       expect(text).toContain('REAL-TIME QA BROWSER CAMPAIGN');
  209 |     });
  210 | 
  211 |     test('donor can view campaign 98', async ({ page }) => {
  212 |       await loginAsDonor(page);
  213 |       const response = await page.goto('/campaign/98');
> 214 |       expect(response?.status()).toBe(200);
      |                                  ^ Error: expect(received).toBe(expected) // Object.is equality
  215 |       await expect(page.locator('body')).toBeVisible();
  216 |     });
  217 | 
  218 |     test('donations page shows existing donations', async ({ page }) => {
  219 |       await loginAsDonor(page);
  220 |       const response = await page.goto('/user/dashboard/donations');
  221 |       expect(response?.status()).toBe(200);
  222 |       await expect(page.locator('body')).toBeVisible();
  223 |     });
  224 | 
  225 |     test('wallet shows correct balance', async ({ page }) => {
  226 |       await loginAsDonor(page);
  227 |       const response = await page.goto('/user/dashboard/wallet');
  228 |       expect(response?.status()).toBe(200);
  229 |       await expect(page.locator('body')).toBeVisible();
  230 |       const text = await page.textContent('body');
  231 |       expect(text).toContain('100');
  232 |     });
  233 |   });
  234 | 
  235 |   test.describe('Console and Network Audit', () => {
  236 |     test('full console and network audit on homepage', async ({ page }) => {
  237 |       const consoleErrors: string[] = [];
  238 |       const consoleWarnings: string[] = [];
  239 |       const networkErrors: { url: string; status: number }[] = [];
  240 | 
  241 |       page.on('console', msg => {
  242 |         if (msg.type() === 'error') consoleErrors.push(msg.text());
  243 |         else if (msg.type() === 'warning') consoleWarnings.push(msg.text());
  244 |       });
  245 | 
  246 |       page.on('response', async response => {
  247 |         if (response.status() >= 400) {
  248 |           networkErrors.push({ url: response.url(), status: response.status() });
  249 |         }
  250 |       });
  251 | 
  252 |       await page.goto('/');
  253 |       await page.waitForLoadState('domcontentloaded');
  254 | 
  255 |       console.log('=== CONSOLE ERRORS ===');
  256 |       consoleErrors.forEach(e => console.log(e));
  257 |       console.log('=== CONSOLE WARNINGS ===');
  258 |       consoleWarnings.forEach(w => console.log(w));
  259 |       console.log('=== NETWORK ERRORS >=400 ===');
  260 |       networkErrors.forEach(n => console.log(`${n.status} ${n.url}`));
  261 | 
  262 |       const appErrors = consoleErrors.filter(e =>
  263 |         !e.includes('unpkg.com') &&
  264 |         !e.includes('cdn.jsdelivr.net') &&
  265 |         !e.includes('cdnjs.cloudflare.com') &&
  266 |         !e.includes('cdn.lordicon.com') &&
  267 |         !e.includes('aos.css') &&
  268 |         !e.includes('aos.js') &&
  269 |         !e.includes('swiper') &&
  270 |         !e.includes('lottie-player') &&
  271 |         !e.includes('vanilla-tilt') &&
  272 |         !e.includes('lucide') &&
  273 |         !e.includes('127.0.0.1:5173')
  274 |       );
  275 |       expect(appErrors.length).toBe(0);
  276 |     });
  277 |   });
  278 | 
  279 |   test.describe('Responsive UI Verification', () => {
  280 |     test('no horizontal overflow on mobile', async ({ page }) => {
  281 |       await page.setViewportSize({ width: 390, height: 844 });
  282 |       await page.goto('/');
  283 |       await page.waitForLoadState('domcontentloaded');
  284 |       const hasOverflow = await page.evaluate(() => {
  285 |         return document.documentElement.scrollWidth > window.innerWidth;
  286 |       });
  287 |       expect(hasOverflow).toBe(false);
  288 |     });
  289 | 
  290 |     test('no horizontal overflow on tablet', async ({ page }) => {
  291 |       await page.setViewportSize({ width: 768, height: 1024 });
  292 |       await page.goto('/');
  293 |       await page.waitForLoadState('domcontentloaded');
  294 |       const hasOverflow = await page.evaluate(() => {
  295 |         return document.documentElement.scrollWidth > window.innerWidth;
  296 |       });
  297 |       expect(hasOverflow).toBe(false);
  298 |     });
  299 | 
  300 |     test('no horizontal overflow on desktop', async ({ page }) => {
  301 |       await page.setViewportSize({ width: 1280, height: 720 });
  302 |       await page.goto('/');
  303 |       await page.waitForLoadState('domcontentloaded');
  304 |       const hasOverflow = await page.evaluate(() => {
  305 |         return document.documentElement.scrollWidth > window.innerWidth;
  306 |       });
  307 |       expect(hasOverflow).toBe(false);
  308 |     });
  309 | 
  310 |     test('dashboard is responsive on mobile', async ({ page }) => {
  311 |       await page.setViewportSize({ width: 375, height: 812 });
  312 |       await loginAsCreator(page);
  313 |       await page.goto('/user/dashboard');
  314 |       await page.waitForLoadState('domcontentloaded');
```