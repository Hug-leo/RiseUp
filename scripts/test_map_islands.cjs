// node scripts/test_map_islands.cjs <path-to-playwright>; XAMPP must be running.
const assert = require('node:assert/strict');
const { execFileSync } = require('node:child_process');
const path = require('node:path');
const { chromium } = require(process.argv[2] || 'playwright');
const root = path.resolve(__dirname, '..').replaceAll('\\', '/');
const base = execFileSync(process.env.PHP_BINARY || 'C:/xampp/php/php.exe', ['-r', `require '${root}/wordpress/wp-load.php'; echo home_url();`], { encoding: 'utf8' });

(async () => {
  const browser = await chromium.launch();
  try {
    const page = await browser.newPage();
    const errors = [];
    page.on('pageerror', error => errors.push(error.message));
    await page.goto(base);
    const mapUrl = await page.locator('img[src*="vietnam-34-provinces.svg"]').first().getAttribute('src');
    await page.goto(mapUrl);
    assert.equal(await page.locator('.island').count(), 17);
    assert.equal(await page.locator('.archipelago').count(), 2);
    const category = execFileSync(process.env.PHP_BINARY || 'C:/xampp/php/php.exe', ['-r', `require '${root}/wordpress/wp-load.php'; echo get_category_link(get_category_by_slug('ban-do-vuon-len'));`], { encoding: 'utf8' });
    await page.goto(category);
    for (const width of [1280, 390]) {
      await page.setViewportSize({ width, height: 1000 });
      for (const mode of ['63', '34', '63']) {
        await page.locator(`.map-toggle-btn[data-map="${mode}"]`).click();
        await page.waitForFunction(count => document.querySelectorAll('.map-province').length === count, Number(mode));
        assert.equal(await page.locator('.map-island').count(), 17);
        assert.equal(await page.locator('.map-island--archipelago').count(), 2);
        const clipped = await page.locator('.map-islands text').evaluateAll(labels => labels.filter(label => {
          const box = label.getBoundingClientRect();
          const svg = label.ownerSVGElement.getBoundingClientRect();
          return box.left < svg.left || box.right > svg.right || box.top < svg.top || box.bottom > svg.bottom;
        }).map(label => label.textContent));
        assert.deepEqual(clipped, [], 'Island labels must stay inside the map');
        const outside = await page.locator('.map-province').evaluateAll(paths => paths.filter(path => {
          const box = path.getBBox();
          const view = path.ownerSVGElement.viewBox.baseVal;
          return box.x < 0 || box.y < 0 || box.x + box.width > view.width || box.y + box.height > view.height;
        }).map(path => path.dataset.province));
        assert.deepEqual(outside, [], 'All province and offshore geometry must stay inside the map');
        const province = page.locator('.map-province').first();
        await province.focus();
        await page.keyboard.press('Enter');
        assert.equal(await province.getAttribute('aria-pressed'), 'true');
        assert.equal(await page.locator('#student-map-detail-title').innerText(), await province.getAttribute('data-province'));
        assert.equal(await page.evaluate(() => document.documentElement.scrollWidth > innerWidth), false);
        await page.locator('#student-map-canvas').screenshot({ path: path.join(root, `docs/screenshots/map-islands-${mode}-${width}.png`) });
      }
    }
    assert.deepEqual(errors, []);
    console.log('PASS: homepage islands; 34/63 toggles; 17 island groups; unclipped labels; keyboard selection; desktop/mobile; no JS errors.');
  } finally {
    await browser.close();
  }
})().catch(error => { console.error(error); process.exitCode = 1; });
