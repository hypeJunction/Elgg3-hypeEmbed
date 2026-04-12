import { test, expect } from '@playwright/test';
import { loginAs } from '../helpers/elgg';

test.describe('hypeEmbed toolbar integration', () => {
  test('longtext input carries embed toolbar on a form that uses it', async ({ page }) => {
    await loginAs(page, 'testuser');
    // The toolbar extends input/longtext; the user profile edit page has a longtext.
    // Try a couple of common pages that include a longtext input.
    const candidates = ['/profile/edit', '/blog/add', '/thewire/add'];
    let foundToolbar = false;
    for (const url of candidates) {
      const resp = await page.goto(url).catch(() => null);
      if (!resp || resp.status() >= 400) continue;
      const has = await page.locator('.embed-menu, [class*="embed-control"], .embed-toolbar').count();
      if (has > 0) {
        foundToolbar = true;
        break;
      }
    }
    // Soft assertion — toolbar presence indicates the view_extension is wired up.
    expect(foundToolbar).toBeTruthy();
  });
});
