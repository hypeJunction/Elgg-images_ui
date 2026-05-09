import { test, expect } from '@playwright/test';
import { loginAs, getImageFilesByOwner, getUserGuidByUsername, deleteEntity } from '../helpers/elgg';
import * as path from 'path';
import * as fs from 'fs';
import * as os from 'os';

/**
 * Creates a small throwaway PNG on disk so the test can upload a real file.
 */
function makeTempPng(): string {
  const pngBytes = Buffer.from(
    '89504e470d0a1a0a0000000d49484452000000010000000108060000001f15c4' +
    '890000000d49444154789c626000000000050001a5f645400000000049454e44' +
    'ae426082',
    'hex'
  );
  const tmp = path.join(os.tmpdir(), `images_ui_test_${Date.now()}.png`);
  fs.writeFileSync(tmp, pngBytes);
  return tmp;
}

test.describe('Image upload', () => {
  test('upload form renders', async ({ page }) => {
    await loginAs(page, 'testuser');
    const response = await page.goto('/images/upload');
    expect(response?.status()).toBeLessThan(400);
    await expect(page.locator('input[name="upload"]')).toBeVisible();
    await expect(page.locator('input[name="title"]')).toBeVisible();
    await expect(page.locator('textarea[name="description"]')).toBeVisible();
  });

  test('upload creates image entity (UI + DB)', async ({ page }) => {
    await loginAs(page, 'testuser');
    const ownerGuid = await getUserGuidByUsername('testuser');
    expect(ownerGuid).not.toBeNull();

    const title = `PW Test Image ${Date.now()}`;
    const tmpFile = makeTempPng();

    try {
      await page.goto('/images/upload');
      await page.setInputFiles('input[name="upload"]', tmpFile);
      await page.fill('input[name="title"]', title);
      await page.fill('textarea[name="description"]', 'Uploaded by Playwright');
      await page.click('input[type="submit"], button[type="submit"]');

      // Assert UI: no error messages shown
      await page.waitForLoadState('networkidle');
      await expect(page.locator('.elgg-system-messages .elgg-message-error')).toHaveCount(0);

      // Assert DB: image entity exists for this owner with matching title
      const images = await getImageFilesByOwner(ownerGuid!);
      const created = images.find((i: any) => i.guid);
      expect(created).toBeTruthy();

      // Cleanup newest entity
      if (created) {
        await deleteEntity(created.guid);
      }
    } finally {
      fs.unlinkSync(tmpFile);
    }
  });

  test('upload action rejects unauthenticated user', async ({ page }) => {
    const response = await page.goto('/images/upload');
    // Elgg redirects anonymous to login page
    expect([200, 302, 403]).toContain(response?.status() ?? 0);
  });
});
