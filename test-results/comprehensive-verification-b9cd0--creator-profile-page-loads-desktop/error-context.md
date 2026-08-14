# Instructions

- Following Playwright test failed.
- Explain why, be concise, respect Playwright best practices.
- Provide a snippet of code with the fix, if possible.

# Test info

- Name: comprehensive-verification.spec.ts >> Comprehensive Independent Verification >> Dashboard Inner Pages - Creator >> creator profile page loads
- Location: tests\browser\comprehensive-verification.spec.ts:50:5

# Error details

```
Error: expect(received).toBe(expected) // Object.is equality

Expected: 200
Received: 404
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
        - link "Log in" [ref=f2e22] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/login
        - link "Get Started" [ref=f2e24] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/register
  - main [ref=f2e26]:
    - generic [ref=f2e29]:
      - generic [ref=f2e30]: "404"
      - generic [ref=f2e31]: Page Not Found
      - heading "Looks like you're lost in the crowd" [level=1] [ref=f2e32]
      - paragraph [ref=f2e33]: The page you're looking for doesn't exist or has been moved. Let's get you back on track.
      - generic [ref=f2e34]:
        - link "Back to Home" [ref=f2e35] [cursor=pointer]:
          - /url: /
        - button "Go Back" [ref=f2e40] [cursor=pointer]
  - contentinfo [ref=f2e44]:
    - generic [ref=f2e45]:
      - generic [ref=f2e46]:
        - generic [ref=f2e47]:
          - generic [ref=f2e48]: Live campaigns
          - generic [ref=f2e50]:
            - heading "Ready to Make an Impact?" [level=2] [ref=f2e51]
            - paragraph [ref=f2e52]: Join thousands of donors changing lives every single day.
          - generic [ref=f2e53]:
            - generic [ref=f2e54]:
              - generic [ref=f2e55]: "0"
              - generic [ref=f2e56]: Donors
            - generic [ref=f2e57]:
              - generic [ref=f2e58]: ₹0
              - generic [ref=f2e59]: Raised
            - generic [ref=f2e60]:
              - generic [ref=f2e61]: "0"
              - generic [ref=f2e62]: Campaigns
        - generic [ref=f2e63]:
          - link "Explore Campaigns" [ref=f2e64] [cursor=pointer]:
            - /url: http://127.0.0.1:8000/all-campaigns
          - link "Start a Fundraiser →" [ref=f2e68] [cursor=pointer]:
            - /url: http://127.0.0.1:8000/campaign/create
            - generic [ref=f2e69]:
              - text: Start a Fundraiser
              - generic [ref=f2e70]: →
      - generic [ref=f2e71]:
        - generic [ref=f2e72]:
          - generic [ref=f2e73]: DonateBazaar
          - paragraph [ref=f2e74]: A trusted platform connecting donors with verified causes. Transparent, secure, and impactful giving for a better world.
          - generic [ref=f2e75]: 2.5M+ donors trust us
          - generic [ref=f2e77]:
            - link [ref=f2e78] [cursor=pointer]:
              - /url: https://www.facebook.com/
            - link [ref=f2e82] [cursor=pointer]:
              - /url: https://x.com/
            - link [ref=f2e86] [cursor=pointer]:
              - /url: https://www.instagram.com/
            - link [ref=f2e91] [cursor=pointer]:
              - /url: https://www.linkedin.com/
            - link [ref=f2e97] [cursor=pointer]:
              - /url: https://www.youtube.com/
        - generic [ref=f2e102]:
          - heading "Platform" [level=3] [ref=f2e103]
          - list [ref=f2e104]:
            - listitem [ref=f2e105]:
              - link "Home" [ref=f2e106] [cursor=pointer]:
                - /url: http://127.0.0.1:8000
            - listitem [ref=f2e107]:
              - link "Campaigns" [ref=f2e108] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/all-campaigns
            - listitem [ref=f2e109]:
              - link "Start Fundraiser" [ref=f2e110] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/campaign/create
            - listitem [ref=f2e111]:
              - link "Search" [ref=f2e112] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/search
            - listitem [ref=f2e113]:
              - link "Impact Stories" [ref=f2e114] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/impact
            - listitem [ref=f2e115]:
              - link "How It Works" [ref=f2e116] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/how-it-works
            - listitem [ref=f2e117]:
              - link "Disaster Relief" [ref=f2e118] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/disaster-relief
        - generic [ref=f2e119]:
          - heading "Company" [level=3] [ref=f2e120]
          - list [ref=f2e121]:
            - listitem [ref=f2e122]:
              - link "About Us" [ref=f2e123] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/about
            - listitem [ref=f2e124]:
              - link "Contact" [ref=f2e125] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/contact
            - listitem [ref=f2e126]:
              - link "Careers" [ref=f2e127] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/career
            - listitem [ref=f2e128]:
              - link "Blog" [ref=f2e129] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/blog
            - listitem [ref=f2e130]:
              - link "Partnership" [ref=f2e131] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/partnership
        - generic [ref=f2e132]:
          - heading "Stay Updated" [level=3] [ref=f2e133]
          - paragraph [ref=f2e134]: Get inspiring stories, new campaigns, and impact reports — straight to your inbox.
          - generic [ref=f2e135]:
            - generic [ref=f2e136]: Your email
            - generic [ref=f2e138]:
              - textbox "Email for newsletter" [ref=f2e139]:
                - /placeholder: Your email
              - button "Subscribe" [ref=f2e140] [cursor=pointer]
          - generic [ref=f2e142]:
            - generic [ref=f2e143]: 10K+ subscribers
            - generic [ref=f2e145]: No spam, ever
      - generic [ref=f2e148]:
        - generic [ref=f2e149]: © 2026 DonateBazaar. All rights reserved.
        - generic [ref=f2e150]:
          - text: Made with
          - generic [ref=f2e151]: ♥
          - text: for a better world
        - generic [ref=f2e152]:
          - generic [ref=f2e153]:
            - link "Privacy" [ref=f2e154] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/privacy-policy
            - link "Terms" [ref=f2e155] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/terms-of-service
            - link "Refunds" [ref=f2e156] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/refund-cancellation
            - link "Cookies" [ref=f2e157] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/cookie-policy
            - link "FAQ" [ref=f2e158] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/faq
          - button "Back to top" [ref=f2e159] [cursor=pointer]
  - button "Open chat" [ref=f2e164] [cursor=pointer]:
    - generic [ref=f2e165]: 
```

# Test source

```ts
  1   | import { test, expect, type Page } from '@playwright/test';
  2   | 
  3   | const BASE_URL = 'http://127.0.0.1:8000';
  4   | const CREATOR_EMAIL = 'simlandikanchan@gmail.com';
  5   | const CREATOR_PASSWORD = 'QaPass@2026!';
  6   | const DONOR_EMAIL = 'simlandikanchan2@gmail.com';
  7   | const DONOR_PASSWORD = 'QaPass@2026!';
  8   | const ADMIN_EMAIL = 'admin@DonateBazaar.com';
  9   | const ADMIN_PASSWORD = 'password';
  10  | 
  11  | async function loginAsCreator(page: Page) {
  12  |   await page.goto('/login');
  13  |   await page.fill('input[name="email"]', CREATOR_EMAIL);
  14  |   await page.fill('input[name="password"]', CREATOR_PASSWORD);
  15  |   await page.click('button[type="submit"]');
  16  |   await page.waitForURL('**/dashboard');
  17  | }
  18  | 
  19  | async function loginAsDonor(page: Page) {
  20  |   await page.goto('/login');
  21  |   await page.fill('input[name="email"]', DONOR_EMAIL);
  22  |   await page.fill('input[name="password"]', DONOR_PASSWORD);
  23  |   await page.click('button[type="submit"]');
  24  |   await page.waitForURL('**/dashboard');
  25  | }
  26  | 
  27  | async function loginAsAdmin(page: Page) {
  28  |   await page.goto('/login');
  29  |   await page.fill('input[name="email"]', ADMIN_EMAIL);
  30  |   await page.fill('input[name="password"]', ADMIN_PASSWORD);
  31  |   await page.click('button[type="submit"]');
  32  |   await page.waitForURL('**/admin/**');
  33  | }
  34  | 
  35  | test.describe('Comprehensive Independent Verification', () => {
  36  |   test.beforeEach(async ({ page }) => {
  37  |     page.setDefaultTimeout(30_000);
  38  |   });
  39  | 
  40  |   test.describe('Dashboard Inner Pages - Creator', () => {
  41  |     test('creator dashboard loads with all sections', async ({ page }) => {
  42  |       await loginAsCreator(page);
  43  |       await page.goto('/user/dashboard');
  44  |       await page.waitForLoadState('domcontentloaded');
  45  |       await expect(page.locator('body')).toBeVisible();
  46  |       const bodyText = await page.textContent('body');
  47  |       expect(bodyText?.length).toBeGreaterThan(100);
  48  |     });
  49  | 
  50  |     test('creator profile page loads', async ({ page }) => {
  51  |       await loginAsCreator(page);
  52  |       const response = await page.goto('/user/profile');
> 53  |       expect(response?.status()).toBe(200);
      |                                  ^ Error: expect(received).toBe(expected) // Object.is equality
  54  |       await expect(page.locator('body')).toBeVisible();
  55  |     });
  56  | 
  57  |     test('creator campaigns page loads', async ({ page }) => {
  58  |       await loginAsCreator(page);
  59  |       const response = await page.goto('/user/dashboard/campaigns');
  60  |       expect(response?.status()).toBe(200);
  61  |       await expect(page.locator('body')).toBeVisible();
  62  |     });
  63  | 
  64  |     test('creator wallet page loads', async ({ page }) => {
  65  |       await loginAsCreator(page);
  66  |       const response = await page.goto('/user/dashboard/wallet');
  67  |       expect(response?.status()).toBe(200);
  68  |       await expect(page.locator('body')).toBeVisible();
  69  |     });
  70  | 
  71  |     test('creator donations page loads', async ({ page }) => {
  72  |       await loginAsCreator(page);
  73  |       const response = await page.goto('/user/dashboard/donations');
  74  |       expect(response?.status()).toBe(200);
  75  |       await expect(page.locator('body')).toBeVisible();
  76  |     });
  77  | 
  78  |     test('creator settlements page loads', async ({ page }) => {
  79  |       await loginAsCreator(page);
  80  |       const response = await page.goto('/user/dashboard/settlements');
  81  |       expect(response?.status()).toBe(200);
  82  |       await expect(page.locator('body')).toBeVisible();
  83  |     });
  84  | 
  85  |     test('creator KYC page loads', async ({ page }) => {
  86  |       await loginAsCreator(page);
  87  |       const response = await page.goto('/user/kyc');
  88  |       expect(response?.status()).toBe(200);
  89  |       await expect(page.locator('body')).toBeVisible();
  90  |     });
  91  | 
  92  |     test('creator blogs page loads', async ({ page }) => {
  93  |       await loginAsCreator(page);
  94  |       const response = await page.goto('/user/dashboard/blogs');
  95  |       expect(response?.status()).toBe(200);
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
```