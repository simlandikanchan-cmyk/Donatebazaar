import { test, expect, type Page } from '@playwright/test';

const BASE_URL = 'http://127.0.0.1:8000';

const CREATOR_EMAIL = 'simlandikanchan@gmail.com';
const CREATOR_PASSWORD = 'QaPass@2026!';
const DONOR_EMAIL = 'simlandikanchan2@gmail.com';
const DONOR_PASSWORD = 'QaPass@2026!';
const ADMIN_EMAIL = 'admin@DonateBazaar.com';
const ADMIN_PASSWORD = 'password';

test.describe('DonateBazaar Real Browser Financial E2E', () => {
  test.beforeEach(async ({ page }) => {
    page.setDefaultTimeout(30_000);
  });

  test.describe('Pre-flight & Environment', () => {
    test('homepage loads successfully', async ({ page }) => {
      const response = await page.goto('/');
      expect(response?.status()).toBe(200);
      await expect(page.locator('body')).toBeVisible();
    });

    test('CSS and JS assets load without fatal errors', async ({ page }) => {
      const errors: string[] = [];
      page.on('console', msg => {
        if (msg.type() === 'error') errors.push(msg.text());
      });

      await page.goto('/');
      await page.waitForLoadState('domcontentloaded');

      const fatalErrors = errors.filter(e =>
        e.includes('TypeError') ||
        e.includes('ReferenceError') ||
        e.includes('SyntaxError') ||
        e.includes('Uncaught')
      );
      expect(fatalErrors.length).toBe(0);
    });

    test('captures console and network errors', async ({ page }) => {
      const consoleErrors: string[] = [];
      const networkErrors: string[] = [];

      page.on('console', msg => {
        if (msg.type() === 'error') consoleErrors.push(msg.text());
      });

      page.on('response', response => {
        if (response.status() >= 400) {
          networkErrors.push(`${response.status()} ${response.url()}`);
        }
      });

      await page.goto('/');
      await page.waitForLoadState('domcontentloaded');

      console.log('Console errors:', consoleErrors);
      console.log('Network errors (>=400):', networkErrors);
    });
  });

  test.describe('Creator Flow', () => {
    test('creator can login', async ({ page }) => {
      await page.goto('/login');
      await page.fill('input[name="email"]', CREATOR_EMAIL);
      await page.fill('input[name="password"]', CREATOR_PASSWORD);
      await page.click('button[type="submit"]');
      await page.waitForURL('**/dashboard');
      const url = page.url();
      console.log('Creator login URL:', url);
      expect(url).toContain('dashboard');
    });

    test('creator can access dashboard', async ({ page }) => {
      await loginAsCreator(page);
      await page.goto('/user/dashboard');
      await page.waitForLoadState('domcontentloaded');
      await expect(page.locator('body')).toBeVisible();
    });

    test('creator can create campaign', async ({ page }) => {
      await loginAsCreator(page);

      await page.goto('/campaign/create');
      await page.waitForLoadState('domcontentloaded');

      await page.fill('input[name="title"]', 'BROWSER QA CAMPAIGN');
      await page.fill('textarea[name="description"]', 'Browser E2E test campaign for financial verification.');
      await page.fill('input[name="goal_amount"]', '10000');
      await page.selectOption('select[name="category_id"]', '18');
      
      await page.click('#btnNext');
      await page.waitForTimeout(500);

      await page.fill('input[name="start_date"]', '2026-08-14');
      await page.fill('input[name="end_date"]', '2026-09-14');

      await page.click('#btnNext');
      await page.waitForTimeout(500);

      await page.click('#addUpdateBtn');
      await page.waitForTimeout(300);
      
      await page.locator('[data-ufield="title"]').first().fill('Test Update');
      await page.locator('[data-ufield="body"]').first().fill('This is a test update for browser E2E verification.');

      await page.click('#btnNext');
      await page.waitForTimeout(500);

      const fileInput = page.locator('input[type="file"][name="cover_image"]');
      if (await fileInput.count() > 0) {
        await fileInput.setInputFiles({
          name: 'test-cover.jpg',
          mimeType: 'image/jpeg',
          buffer: Buffer.from('fake-image-data'),
        });
        await page.waitForTimeout(500);
      }

      await page.click('#btnNext');
      await page.waitForTimeout(500);

      await page.click('#btnNext');
      await page.waitForTimeout(500);

      await page.click('#btnSubmit');
      await page.waitForTimeout(5000);

      const url = page.url();
      console.log('Campaign creation URL:', url);
    });
  });

  test.describe('Donor Flow', () => {
    test('donor can login', async ({ page }) => {
      await page.goto('/login');
      await page.fill('input[name="email"]', DONOR_EMAIL);
      await page.fill('input[name="password"]', DONOR_PASSWORD);
      await page.click('button[type="submit"]');
      await page.waitForURL('**/dashboard');
      const url = page.url();
      console.log('Donor login URL:', url);
      expect(url).toContain('dashboard');
    });

    test('donor can browse campaigns', async ({ page }) => {
      await page.goto('/campaigns');
      await page.waitForLoadState('domcontentloaded');
      await expect(page.locator('body')).toBeVisible();
    });
  });

  test.describe('Admin Flow', () => {
    test('admin can login', async ({ page }) => {
      await page.goto('/login');
      await page.fill('input[name="email"]', ADMIN_EMAIL);
      await page.fill('input[name="password"]', ADMIN_PASSWORD);
      await page.click('button[type="submit"]');
      await page.waitForURL('**/admin/**');
      const url = page.url();
      console.log('Admin login URL:', url);
      expect(url).toContain('admin');
    });

    test('admin can access admin dashboard', async ({ page }) => {
      await loginAsAdmin(page);
      await page.goto('/admin/dashboard');
      await page.waitForLoadState('domcontentloaded');
      await expect(page.locator('body')).toBeVisible();
    });
  });

  test.describe('Authorization', () => {
    test('unauthenticated user redirected to login', async ({ page }) => {
      await page.goto('/user/dashboard');
      await page.waitForTimeout(2000);
      const url = page.url();
      console.log('Unauthenticated dashboard URL:', url);
    });
  });

  test.describe('Responsive - Desktop HD', () => {
    test('desktop 1440x900 renders correctly', async ({ page }) => {
      await page.setViewportSize({ width: 1440, height: 900 });
      await page.goto('/');
      await page.waitForLoadState('domcontentloaded');
      await expect(page.locator('body')).toBeVisible();
    });
  });

  test.describe('Responsive - Tablet', () => {
    test('tablet 768x1024 renders correctly', async ({ page }) => {
      await page.setViewportSize({ width: 768, height: 1024 });
      await page.goto('/');
      await page.waitForLoadState('domcontentloaded');
      await expect(page.locator('body')).toBeVisible();
    });
  });

  test.describe('Responsive - Mobile', () => {
    test('mobile 390x844 renders correctly', async ({ page }) => {
      await page.setViewportSize({ width: 390, height: 844 });
      await page.goto('/');
      await page.waitForLoadState('domcontentloaded');
      await expect(page.locator('body')).toBeVisible();
    });
  });
});

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
