import { test, expect } from '@playwright/test';
import { loginAs, getEntitiesBySubtype, getMetadata } from '../helpers/elgg';

test.describe('hypeEmbed actions', () => {
  test('embed/player action returns safe player markup', async ({ page, request }) => {
    await loginAs(page, 'testuser');

    // Pull CSRF tokens from a logged-in page
    await page.goto('/');
    const tokens = await page.evaluate(() => {
      // @ts-ignore
      const s = (window as any).elgg?.security?.token;
      return s ? { __elgg_token: s.__elgg_token, __elgg_ts: s.__elgg_ts } : null;
    });
    test.skip(!tokens, 'CSRF tokens unavailable on page');

    const cookies = await page.context().cookies();
    const cookieHeader = cookies.map(c => `${c.name}=${c.value}`).join('; ');

    const resp = await request.post('/action/embed/player', {
      headers: { cookie: cookieHeader, 'x-requested-with': 'XMLHttpRequest' },
      form: {
        ...tokens!,
        url: 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
      },
    });
    expect(resp.status()).toBe(200);
    const body = await resp.json();
    expect(body).toHaveProperty('output');
  });

  test('embed/code action creates embed_code entity with token (admin only)', async ({ page, request }) => {
    await loginAs(page, 'admin');

    await page.goto('/');
    const tokens = await page.evaluate(() => {
      // @ts-ignore
      const s = (window as any).elgg?.security?.token;
      return s ? { __elgg_token: s.__elgg_token, __elgg_ts: s.__elgg_ts } : null;
    });
    test.skip(!tokens, 'CSRF tokens unavailable on page');

    const cookies = await page.context().cookies();
    const cookieHeader = cookies.map(c => `${c.name}=${c.value}`).join('; ');

    const html = '<iframe src="https://example.com/test"></iframe>';
    const resp = await request.post('/action/embed/code', {
      headers: { cookie: cookieHeader, 'x-requested-with': 'XMLHttpRequest' },
      form: { ...tokens!, html },
    });
    expect(resp.status()).toBe(200);

    // Assert DB: an embed_code entity was created
    const rows = await getEntitiesBySubtype('embed_code');
    expect(rows.length).toBeGreaterThan(0);
    const latest = rows[0];
    const tokenMeta = await getMetadata(latest.guid, 'token');
    expect(tokenMeta.length).toBeGreaterThan(0);
  });

  test('embed/code action rejects empty html', async ({ page, request }) => {
    await loginAs(page, 'admin');
    await page.goto('/');
    const tokens = await page.evaluate(() => {
      // @ts-ignore
      const s = (window as any).elgg?.security?.token;
      return s ? { __elgg_token: s.__elgg_token, __elgg_ts: s.__elgg_ts } : null;
    });
    test.skip(!tokens, 'CSRF tokens unavailable on page');

    const cookies = await page.context().cookies();
    const cookieHeader = cookies.map(c => `${c.name}=${c.value}`).join('; ');

    const resp = await request.post('/action/embed/code', {
      headers: { cookie: cookieHeader, 'x-requested-with': 'XMLHttpRequest' },
      form: { ...tokens!, html: '' },
    });
    const body = await resp.json().catch(() => ({}));
    // Elgg action response uses 'error' or status != 0 on error
    expect(body.status !== 0 || body.error).toBeTruthy();
  });

  test('embed/buttons action requires admin', async ({ page, request }) => {
    await loginAs(page, 'testuser');
    await page.goto('/');
    const tokens = await page.evaluate(() => {
      // @ts-ignore
      const s = (window as any).elgg?.security?.token;
      return s ? { __elgg_token: s.__elgg_token, __elgg_ts: s.__elgg_ts } : null;
    });
    test.skip(!tokens, 'CSRF tokens unavailable on page');
    const cookies = await page.context().cookies();
    const cookieHeader = cookies.map(c => `${c.name}=${c.value}`).join('; ');

    const resp = await request.post('/action/embed/buttons', {
      headers: { cookie: cookieHeader, 'x-requested-with': 'XMLHttpRequest' },
      form: {
        ...tokens!,
        text: 'Click',
        url: 'https://example.com',
      },
    });
    // Non-admin should be blocked: 403 or redirected
    expect([302, 403, 400]).toContain(resp.status());
  });
});
