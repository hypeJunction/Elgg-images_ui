import { test, expect } from '@playwright/test';
import { loginAs } from '../helpers/elgg';

test.describe('Site menu integration', () => {
  test('site menu contains Images link', async ({ page }) => {
    await loginAs(page, 'testuser');
    await page.goto('/');
    // images_ui registers a site menu item with href "/images/all"
    const imagesLink = page.locator('a[href$="/images/all"]');
    await expect(imagesLink.first()).toBeVisible();
  });

  test('clicking Images link navigates to listing', async ({ page }) => {
    await loginAs(page, 'testuser');
    await page.goto('/');
    const imagesLink = page.locator('a[href$="/images/all"]').first();
    await imagesLink.click();
    await expect(page).toHaveURL(/\/images\/all/);
  });
});
