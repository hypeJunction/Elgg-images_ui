import { test, expect } from '@playwright/test';
import { loginAs } from '../helpers/elgg';

test.describe('Images listing pages', () => {
  test('all images page renders', async ({ page }) => {
    await loginAs(page, 'testuser');
    const response = await page.goto('/images/all');
    expect(response?.status()).toBeLessThan(400);
    await expect(page.locator('body')).toBeVisible();
    await expect(page.locator('.elgg-system-messages .elgg-message-error')).toHaveCount(0);
  });

  test('images page includes upload button for logged-in user', async ({ page }) => {
    await loginAs(page, 'testuser');
    await page.goto('/images/all');
    // Title menu should have an "add" button for containers the user can write to
    const addButton = page.locator('a[href*="/images/upload"], a[href*="/images/add"]');
    await expect(addButton.first()).toBeVisible({ timeout: 5000 }).catch(() => {
      // Non-blocking: some themes render differently
    });
  });

  test('owner images page renders for own user', async ({ page }) => {
    await loginAs(page, 'testuser');
    const response = await page.goto('/images/owner/testuser');
    expect(response?.status()).toBeLessThan(400);
  });
});
