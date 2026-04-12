import { test, expect } from '@playwright/test';
import { loginAs } from '../helpers/elgg';

test.describe('hypeEmbed tab routes', () => {
  test('embed/posts tab renders for logged-in user', async ({ page }) => {
    await loginAs(page, 'testuser');
    const resp = await page.goto('/embed/posts?embed_lightbox=1');
    expect(resp?.status()).toBe(200);
    // Tab content should render without PHP errors
    await expect(page.locator('body')).not.toContainText('Fatal error');
    await expect(page.locator('body')).not.toContainText('Parse error');
  });

  test('embed/player tab renders', async ({ page }) => {
    await loginAs(page, 'testuser');
    const resp = await page.goto('/embed/player?embed_lightbox=1');
    expect(resp?.status()).toBe(200);
    await expect(page.locator('body')).not.toContainText('Fatal error');
  });

  test('embed/file tab renders', async ({ page }) => {
    await loginAs(page, 'testuser');
    const resp = await page.goto('/embed/file?embed_lightbox=1');
    expect(resp?.status()).toBe(200);
    await expect(page.locator('body')).not.toContainText('Fatal error');
  });

  test('embed admin tabs (buttons/code/assets) render for admin', async ({ page }) => {
    await loginAs(page, 'admin');

    for (const tab of ['buttons', 'code', 'assets']) {
      const resp = await page.goto(`/embed/${tab}?embed_lightbox=1`);
      expect(resp?.status(), `tab=${tab}`).toBe(200);
      await expect(page.locator('body'), `tab=${tab}`).not.toContainText('Fatal error');
    }
  });
});
