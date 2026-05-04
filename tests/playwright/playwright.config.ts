import { defineConfig } from '@playwright/test';

// In Docker: ELGG_BASE_URL=http://elgg (container networking)
export default defineConfig({
  testDir: './tests',
  timeout: 30000,
  use: {
    baseURL: process.env.ELGG_BASE_URL || 'http://elgg',
    ignoreHTTPSErrors: true,
    trace: 'retain-on-failure',
  },
  // Sequential — tests share Elgg DB state
  workers: 1,
  projects: [{ name: 'chromium', use: { browserName: 'chromium' } }],
});
