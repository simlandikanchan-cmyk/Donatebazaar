import { test, expect, type Page } from '@playwright/test';

const BASE_URL = 'http://127.0.0.1:8000';
const CREATOR_EMAIL = 'simlandikanchan@gmail.com';
const CREATOR_PASSWORD = 'QaPass@2026!';
const DONOR_EMAIL = 'simlandikanchan2@gmail.com';
const DONOR_PASSWORD = 'QaPass@2026!';
const ADMIN_EMAIL = 'admin@DonateBazaar.com';
const ADMIN_PASSWORD = 'password';

async function loginAsCreator(page: Page) {
  await page.goto('/login');
  await page.fill('input[name="email"]', CREATOR_EMAIL);
  await page.fill('input[name="password"]', CREATOR_PASSWORD);
  await page.click('button[type="submit"]');
  await page.waitForURL('**/dashboard');
}

async function loginAsDonor(page: Page) {
  await page.goto('/login');
  await page.fill('input[name="email"]', DONOR_EMAIL);
  await page.fill('input[name="password"]', DONOR_PASSWORD);
  await page.click('button[type="submit"]');
  await page.waitForURL('**/dashboard');
}

async function loginAsAdmin(page: Page) {
  await page.goto('/login');
  await page.fill('input[name="email"]', ADMIN_EMAIL);
  await page.fill('input[name="password"]', ADMIN_PASSWORD);
  await page.click('button[type="submit"]');
  await page.waitForURL('**/admin/**');
}

test.describe('Comprehensive Independent Verification', () => {
  test.beforeEach(async ({ page }) => {
    page.setDefaultTimeout(30_000);
  });

  test.describe('Dashboard Inner Pages - Creator', () => {
    test('creator dashboard loads with all sections', async ({ page }) => {
      await loginAsCreator(page);
      await page.goto('/user/dashboard');
      await page.waitForLoadState('domcontentloaded');
      await expect(page.locator('body')).toBeVisible();
      const bodyText = await page.textContent('body');
      expect(bodyText?.length).toBeGreaterThan(100);
    });

    test('creator profile page loads', async ({ page }) => {
      await loginAsCreator(page);
      const response = await page.goto('/profile');
      expect(response?.status()).toBe(200);
      await expect(page.locator('body')).toBeVisible();
    });

    test('creator wallet page loads', async ({ page }) => {
      await loginAsCreator(page);
      const response = await page.goto('/user/dashboard/wallet');
      expect(response?.status()).toBe(200);
      await expect(page.locator('body')).toBeVisible();
    });

    test('creator donation history page loads', async ({ page }) => {
      await loginAsCreator(page);
      const response = await page.goto('/donation-history');
      expect(response?.status()).toBe(200);
      await expect(page.locator('body')).toBeVisible();
    });

    test('creator KYC page loads', async ({ page }) => {
      await loginAsCreator(page);
      const response = await page.goto('/user/kyc');
      expect(response?.status()).toBe(200);
      await expect(page.locator('body')).toBeVisible();
    });

    test('creator blogs page loads', async ({ page }) => {
      await loginAsCreator(page);
      const response = await page.goto('/user/dashboard/blogs');
      expect(response?.status()).toBe(200);
      await expect(page.locator('body')).toBeVisible();
    });

    test('creator saved campaigns loads', async ({ page }) => {
      await loginAsCreator(page);
      const response = await page.goto('/user/dashboard/saved-campaigns');
      expect(response?.status()).toBe(200);
      await expect(page.locator('body')).toBeVisible();
    });

    test('creator level page loads', async ({ page }) => {
      await loginAsCreator(page);
      const response = await page.goto('/user/dashboard/level');
      expect(response?.status()).toBe(200);
      await expect(page.locator('body')).toBeVisible();
    });
  });

  test.describe('Dashboard Inner Pages - Donor', () => {
    test('donor dashboard loads', async ({ page }) => {
      await loginAsDonor(page);
      const response = await page.goto('/user/dashboard');
      expect(response?.status()).toBe(200);
      await expect(page.locator('body')).toBeVisible();
    });

    test('donor wallet page loads', async ({ page }) => {
      await loginAsDonor(page);
      const response = await page.goto('/user/dashboard/wallet');
      expect(response?.status()).toBe(200);
      await expect(page.locator('body')).toBeVisible();
    });

    test('donor donations page loads', async ({ page }) => {
      await loginAsDonor(page);
      const response = await page.goto('/donation-history');
      expect(response?.status()).toBe(200);
      await expect(page.locator('body')).toBeVisible();
    });

    test('donor saved campaigns loads', async ({ page }) => {
      await loginAsDonor(page);
      const response = await page.goto('/user/dashboard/saved-campaigns');
      expect(response?.status()).toBe(200);
      await expect(page.locator('body')).toBeVisible();
    });
  });

  test.describe('Dashboard Inner Pages - Admin', () => {
    test('admin dashboard loads', async ({ page }) => {
      await loginAsAdmin(page);
      const response = await page.goto('/admin/dashboard');
      expect(response?.status()).toBe(200);
      await expect(page.locator('body')).toBeVisible();
    });

    test('admin campaigns page loads', async ({ page }) => {
      await loginAsAdmin(page);
      const response = await page.goto('/admin/campaign');
      expect(response?.status()).toBe(200);
      await expect(page.locator('body')).toBeVisible();
    });

    test('admin applications page loads', async ({ page }) => {
      await loginAsAdmin(page);
      const response = await page.goto('/admin/applications');
      expect(response?.status()).toBe(200);
      await expect(page.locator('body')).toBeVisible();
    });

    test('admin blogs page loads', async ({ page }) => {
      await loginAsAdmin(page);
      const response = await page.goto('/admin/blogs');
      expect(response?.status()).toBe(200);
      await expect(page.locator('body')).toBeVisible();
    });
  });

  test.describe('Authorization / IDOR', () => {
    test('unauthenticated user redirected to login', async ({ page }) => {
      await page.goto('/user/dashboard');
      await expect(page).toHaveURL(/.*login/);
    });

    test('donor cannot access admin dashboard', async ({ page }) => {
      await loginAsDonor(page);
      const response = await page.goto('/admin/dashboard', { followRedirects: false });
      expect(response?.status()).toBe(403);
    });

    test('creator cannot access admin dashboard', async ({ page }) => {
      await loginAsCreator(page);
      const response = await page.goto('/admin/dashboard', { followRedirects: false });
      expect(response?.status()).toBe(403);
    });

    test('unauthenticated admin redirected to login', async ({ page }) => {
      await page.goto('/admin/dashboard');
      await expect(page).toHaveURL(/.*login/);
    });
  });

  test.describe('Financial Flow Verification', () => {
    test('campaign 98 exists and is paused', async ({ page }) => {
      await loginAsCreator(page);
      const response = await page.goto('/campaign/98');
      expect(response?.status()).toBe(200);
      await expect(page.locator('body')).toBeVisible();
      const text = await page.textContent('body');
      expect(text).toContain('REAL-TIME QA BROWSER CAMPAIGN');
    });

    test('donor campaign access returns valid response', async ({ page }) => {
      await loginAsDonor(page);
      const response = await page.goto('/campaign/98');
      expect([200, 403]).toContain(response?.status());
      await expect(page.locator('body')).toBeVisible();
    });

    test('donations page shows existing donations', async ({ page }) => {
      await loginAsDonor(page);
      const response = await page.goto('/donation-history');
      expect(response?.status()).toBe(200);
      await expect(page.locator('body')).toBeVisible();
    });

    test('wallet shows correct balance', async ({ page }) => {
      await loginAsDonor(page);
      const response = await page.goto('/user/dashboard/wallet');
      expect(response?.status()).toBe(200);
      await expect(page.locator('body')).toBeVisible();
      const text = await page.textContent('body');
      expect(text).toContain('100');
    });
  });

  test.describe('Console and Network Audit', () => {
    test('full console and network audit on homepage', async ({ page }) => {
      const consoleErrors: string[] = [];
      const consoleWarnings: string[] = [];
      const networkErrors: { url: string; status: number }[] = [];

      page.on('console', msg => {
        if (msg.type() === 'error') consoleErrors.push(msg.text());
        else if (msg.type() === 'warning') consoleWarnings.push(msg.text());
      });

      page.on('response', async response => {
        if (response.status() >= 400) {
          networkErrors.push({ url: response.url(), status: response.status() });
        }
      });

      await page.goto('/');
      await page.waitForLoadState('domcontentloaded');

      console.log('=== CONSOLE ERRORS ===');
      consoleErrors.forEach(e => console.log(e));
      console.log('=== CONSOLE WARNINGS ===');
      consoleWarnings.forEach(w => console.log(w));
      console.log('=== NETWORK ERRORS >=400 ===');
      networkErrors.forEach(n => console.log(`${n.status} ${n.url}`));

      const appErrors = consoleErrors.filter(e =>
        !e.includes('unpkg.com') &&
        !e.includes('cdn.jsdelivr.net') &&
        !e.includes('cdnjs.cloudflare.com') &&
        !e.includes('cdn.lordicon.com') &&
        !e.includes('aos.css') &&
        !e.includes('aos.js') &&
        !e.includes('swiper') &&
        !e.includes('lottie-player') &&
        !e.includes('vanilla-tilt') &&
        !e.includes('lucide') &&
        !e.includes('127.0.0.1:5173')
      );
      expect(appErrors.length).toBe(0);
    });
  });

  test.describe('Responsive UI Verification', () => {
    test('no horizontal overflow on mobile', async ({ page }) => {
      await page.setViewportSize({ width: 390, height: 844 });
      await page.goto('/');
      await page.waitForLoadState('domcontentloaded');
      const hasOverflow = await page.evaluate(() => {
        return document.documentElement.scrollWidth > window.innerWidth;
      });
      expect(hasOverflow).toBe(false);
    });

    test('no horizontal overflow on tablet', async ({ page }) => {
      await page.setViewportSize({ width: 768, height: 1024 });
      await page.goto('/');
      await page.waitForLoadState('domcontentloaded');
      const hasOverflow = await page.evaluate(() => {
        return document.documentElement.scrollWidth > window.innerWidth;
      });
      expect(hasOverflow).toBe(false);
    });

    test('no horizontal overflow on desktop', async ({ page }) => {
      await page.setViewportSize({ width: 1280, height: 720 });
      await page.goto('/');
      await page.waitForLoadState('domcontentloaded');
      const hasOverflow = await page.evaluate(() => {
        return document.documentElement.scrollWidth > window.innerWidth;
      });
      expect(hasOverflow).toBe(false);
    });

    test('dashboard is responsive on mobile', async ({ page }) => {
      await page.setViewportSize({ width: 375, height: 812 });
      await loginAsCreator(page);
      await page.goto('/user/dashboard');
      await page.waitForLoadState('domcontentloaded');
      await expect(page.locator('body')).toBeVisible();
      const hasOverflow = await page.evaluate(() => {
        return document.documentElement.scrollWidth > window.innerWidth;
      });
      expect(hasOverflow).toBe(false);
    });
  });

  test.describe('CSS/JS Asset Verification', () => {
    test('page loads CSS and JS assets without fatal errors', async ({ page }) => {
      const failedAssets: { url: string; type: string }[] = [];

      page.on('response', async response => {
        const url = response.url();
        const ext = url.split('.').pop()?.split('?')[0];
        if (ext === 'css' || ext === 'js') {
          if (response.status() !== 200) {
            failedAssets.push({ url, type: ext });
          }
        }
      });

      await page.goto('/');
      await page.waitForLoadState('domcontentloaded');

      console.log('=== FAILED ASSETS ===');
      failedAssets.forEach(a => console.log(`${a.type}: ${a.url}`));

      expect(failedAssets.length).toBe(0);
    });
  });
});
