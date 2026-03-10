import fs from 'node:fs';
import path from 'node:path';
import { chromium } from 'playwright';

const baseUrl = process.env.BASE_URL || 'http://127.0.0.1:8000';
const outDir = process.env.OUT_DIR || path.join('artifacts', 'snapshots', new Date().toISOString().replace(/[:.]/g, '-'));

const targets = [
  { name: 'gifts-details', url: '/gifts/11' },
  { name: 'product-details', url: '/products/1086' },
  { name: 'store-grid', url: '/store' },
  { name: 'categories', url: '/categories' },
  { name: 'category-vegetables', url: '/category/vegetables' },
  { name: 'box-arrangement', url: '/gifts/box-arrangement' },
  { name: 'flower-bouquet', url: '/gifts/flower-bouquet' },
];

const viewports = [
  { label: 'desktop', width: 1280, height: 720 },
  { label: 'mobile', width: 390, height: 844, isMobile: true, hasTouch: true },
];

function safeFileName(s) {
  return String(s).replace(/[^a-z0-9_-]+/gi, '-').replace(/-+/g, '-').replace(/(^-|-$)/g, '');
}

fs.mkdirSync(outDir, { recursive: true });

const browser = await chromium.launch();
const report = {
  baseUrl,
  outDir,
  generatedAt: new Date().toISOString(),
  pages: [],
};

for (const target of targets) {
  const fullUrl = new URL(target.url, baseUrl).toString();
  const pageResult = { name: target.name, url: fullUrl, screenshots: [], errors: [] };
  report.pages.push(pageResult);

  for (const vp of viewports) {
    const context = await browser.newContext({
      viewport: { width: vp.width, height: vp.height },
      isMobile: vp.isMobile || false,
      hasTouch: vp.hasTouch || false,
    });

    const page = await context.newPage();
    const errors = [];
    page.on('pageerror', (err) => errors.push({ type: 'pageerror', message: String(err?.message || err) }));
    page.on('console', (msg) => {
      if (msg.type() === 'error') {
        errors.push({ type: 'console', message: msg.text() });
      }
    });

    try {
      await page.goto(fullUrl, { waitUntil: 'networkidle', timeout: 60000 });
      await page.waitForTimeout(250);

      const fileName = `${safeFileName(target.name)}-${vp.label}.png`;
      const filePath = path.join(outDir, fileName);
      await page.screenshot({ path: filePath, fullPage: true });

      pageResult.screenshots.push({ viewport: vp.label, file: filePath });
    } catch (e) {
      errors.push({ type: 'runner', message: String(e?.message || e) });
    } finally {
      pageResult.errors.push(...errors.map((x) => ({ ...x, viewport: vp.label })));
      await context.close();
    }
  }
}

await browser.close();

fs.writeFileSync(path.join(outDir, 'report.json'), JSON.stringify(report, null, 2), 'utf8');
console.log(`Saved snapshots to ${outDir}`);

