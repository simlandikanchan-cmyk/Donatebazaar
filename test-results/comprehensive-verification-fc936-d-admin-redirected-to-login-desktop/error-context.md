# Instructions

- Following Playwright test failed.
- Explain why, be concise, respect Playwright best practices.
- Provide a snippet of code with the fix, if possible.

# Test info

- Name: comprehensive-verification.spec.ts >> Comprehensive Independent Verification >> Authorization / IDOR >> unauthenticated admin redirected to login
- Location: tests\browser\comprehensive-verification.spec.ts:194:5

# Error details

```
Error: expect(received).toBe(expected) // Object.is equality

Expected: 302
Received: 200
```

# Page snapshot

```yaml
- generic [ref=e1]:
  - button "Toggle dark mode" [ref=e2] [cursor=pointer]
  - generic [ref=e6]:
    - generic [ref=e7]:
      - link "DonateBazaar" [ref=e8] [cursor=pointer]:
        - /url: http://127.0.0.1:8000
      - generic [ref=e13]:
        - generic [ref=e14]: Welcome back
        - heading "Good to See You Again" [level=1] [ref=e16]: Good to SeeYou Again
        - paragraph [ref=e17]: Log back in and continue your journey of making a difference. Your campaigns are waiting for you.
      - generic [ref=e18]:
        - generic [ref=e19]: Live activity
        - generic [ref=e21]:
          - generic [ref=e22]:
            - generic [ref=e23]: R
            - generic [ref=e24]:
              - generic [ref=e25]: Rahul M.
              - generic [ref=e26]: Donated to Child Education
            - generic [ref=e27]: +₹500
          - generic [ref=e29]:
            - generic [ref=e30]: P
            - generic [ref=e31]:
              - generic [ref=e32]: Priya S.
              - generic [ref=e33]: Supported Flood Relief Fund
            - generic [ref=e34]: +₹1,200
          - generic [ref=e36]:
            - generic [ref=e37]: A
            - generic [ref=e38]:
              - generic [ref=e39]: Amit K.
              - generic [ref=e40]: Funded Animal Rescue Drive
            - generic [ref=e41]: +₹750
      - list [ref=e42]:
        - listitem [ref=e43]: 100% secure & bank-grade encrypted
        - listitem [ref=e47]: 2.5M+ donors already trust us
        - listitem [ref=e51]: ₹50Cr+ raised for good causes
      - generic [ref=e55]:
        - generic [ref=e56]:
          - generic [ref=e57]: 2.5M+
          - generic [ref=e58]: Donors
        - generic [ref=e59]:
          - generic [ref=e60]: 10K+
          - generic [ref=e61]: Campaigns
        - generic [ref=e62]:
          - generic [ref=e63]: ₹50Cr+
          - generic [ref=e64]: Raised
    - generic [ref=e65]:
      - generic [ref=e66]:
        - heading "Welcome back" [level=2] [ref=e67]
        - paragraph [ref=e68]: Sign in to your DonateBazaar account
      - generic [ref=e70]:
        - generic [ref=e71]:
          - generic [ref=e72]: Email Address
          - textbox "Email Address" [active] [ref=e74]:
            - /placeholder: rahul@example.com
        - generic [ref=e75]:
          - generic [ref=e76]: Password
          - generic [ref=e77]:
            - textbox "Password" [ref=e78]:
              - /placeholder: Enter your password
            - button "Show password" [ref=e79] [cursor=pointer]
        - generic [ref=e83]:
          - generic [ref=e84] [cursor=pointer]:
            - checkbox "Remember me" [ref=e85]
            - text: Remember me
          - link "Forgot password?" [ref=e86] [cursor=pointer]:
            - /url: http://127.0.0.1:8000/forgot-password
        - button "Log In to Your Account" [ref=e87] [cursor=pointer]
        - generic [ref=e92]: or
        - link "Continue with Google" [ref=e94] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/auth/google
        - link "Continue with Phone" [ref=e101] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/otp-login
        - paragraph [ref=e105]:
          - text: Don't have an account?
          - link "Create one free" [ref=e106] [cursor=pointer]:
            - /url: http://127.0.0.1:8000/register
```

# Test source

```ts
  96  |       await expect(page.locator('body')).toBeVisible();
  97  |     });
  98  | 
  99  |     test('creator saved campaigns loads', async ({ page }) => {
  100 |       await loginAsCreator(page);
  101 |       const response = await page.goto('/user/dashboard/saved-campaigns');
  102 |       expect(response?.status()).toBe(200);
  103 |       await expect(page.locator('body')).toBeVisible();
  104 |     });
  105 | 
  106 |     test('creator level page loads', async ({ page }) => {
  107 |       await loginAsCreator(page);
  108 |       const response = await page.goto('/user/dashboard/level');
  109 |       expect(response?.status()).toBe(200);
  110 |       await expect(page.locator('body')).toBeVisible();
  111 |     });
  112 |   });
  113 | 
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
> 196 |       expect(response?.status()).toBe(302);
      |                                  ^ Error: expect(received).toBe(expected) // Object.is equality
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
  214 |       expect(response?.status()).toBe(200);
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
```