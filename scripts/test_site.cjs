// node scripts/test_site.cjs <path-to-playwright>; XAMPP must be running.
const assert = require('node:assert/strict');
const { execFileSync } = require('node:child_process');
const path = require('node:path');
const { chromium } = require(process.argv[2] || 'playwright');
const root = path.resolve(__dirname, '..').replaceAll('\\', '/');
const php = code => execFileSync(process.env.PHP_BINARY || 'C:/xampp/php/php.exe', ['-r', `require '${root}/wordpress/wp-load.php';${code}`], { encoding: 'utf8' });
const check = (value, label) => { assert.ok(value, label); console.log('PASS: ' + label); };
let browser, user;
(async () => {
  try {
    const base = php('echo home_url();');
    browser = await chromium.launch();
    const context = await browser.newContext();
    const page = await context.newPage();
    const errors = [];
    page.on('pageerror', e => errors.push(e.message));
    await page.goto(base + '/', { waitUntil: 'domcontentloaded' });
    const links = await page.locator('a[href]').evaluateAll(elements => [...new Set(elements.map(e => e.href).filter(h => h.startsWith(location.origin + '/sampleweb/wordpress/') && !/wp-admin|wp-login|logout|#|\?/.test(h)))]);
    for (const url of links) check((await context.request.get(url)).status() < 400, 'Internal link: ' + url.replace(base, ''));
    const en = await context.request.get(base + '/?lang=en');
    check(en.headersArray().filter(h => h.name.toLowerCase() === 'set-cookie' && h.value.startsWith('charity_lang=')).length === 1, 'Language cookie written once');
    await page.goto(base + '/', { waitUntil: 'domcontentloaded' });
    check(await page.locator('.header__site-name').innerText() === 'Rise Up', 'Language persists');
    await page.goto(base + '/?lang=vi', { waitUntil: 'domcontentloaded' });
    await page.setViewportSize({ width: 390, height: 850 });
    await page.locator('.nav-toggle').click();
    await page.setViewportSize({ width: 1920, height: 1000 });
    await page.waitForFunction(() => document.body.style.overflow !== 'hidden');
    check(await page.locator('.nav-toggle').getAttribute('aria-expanded') === 'false', 'Resize restores scrolling and closes menu');
    await page.setViewportSize({ width: 390, height: 850 });
    await page.locator('.nav-toggle').click();
    await page.keyboard.press('Escape');
    check(await page.locator('.nav-toggle').getAttribute('aria-expanded') === 'false', 'Escape closes menu');
    const login = base + '/dang-nhap-cong-tac-vien/';
    const malformed = await context.request.post(login, { form: { 'charity_auth_action[]': 'login' } });
    check(malformed.status() === 400, 'Malformed login rejected without PHP error');
    user = JSON.parse(php(`$password=wp_generate_password(24);$login='site_test_'.wp_generate_password(12,false);$id=wp_insert_user(['user_login'=>$login,'user_pass'=>$password,'role'=>'riseup_collaborator']);if(is_wp_error($id)){exit(1);}echo json_encode(['id'=>$id,'login'=>$login,'password'=>$password]);`));
    await page.goto(login, { waitUntil: 'domcontentloaded' });
    await page.locator('#auth-log').fill(user.login);
    await page.locator('#auth-pwd').fill(user.password);
    await page.locator('.auth-form button').click();
    await page.waitForURL(/wp-admin\/edit.php/);
    check(true, 'Collaborator signs in through real login form');
    await page.goto(base + '/', { waitUntil: 'domcontentloaded' });
    const nonce = await page.evaluate(() => charityHCM.nonce);
    const privateId = Number(php(`echo wp_insert_post(['post_type'=>'riseup_contact','post_status'=>'private','post_title'=>'Site test private','post_author'=>${user.id}]);`));
    const blocked = await context.request.post(base + '/wp-admin/admin-ajax.php', { form: { action: 'toggle_post_like', nonce, post_id: String(privateId) } });
    check(blocked.status() === 403, 'Like cannot modify private contact');
    check(php(`echo get_post_meta(${privateId},'_post_likes',true);`) === '', 'Private contact unchanged');
    // Nonces bind to the browser session, so generate using its logged-in cookie.
    const cookie = (await context.cookies()).find(c => c.name.startsWith('wordpress_logged_in_'));
    const browserNonce = php(`$_COOKIE[LOGGED_IN_COOKIE]=rawurldecode('${cookie.value}');wp_set_current_user(${user.id});echo wp_create_nonce('vuonlen_submit_post');`);
    const body = "<p>Kiểm tra O'Reilly C:\\notes\\new</p>";
    const submitted = await context.request.post(base + '/wp-admin/admin-ajax.php', { form: { action: 'vuonlen_submit_post', nonce: browserNonce, post_title: 'Site test pending', post_content: body } });
    check((await submitted.json()).success, 'Post submission succeeds');
    const posts = JSON.parse(php(`echo json_encode(get_posts(['author'=>${user.id},'post_status'=>'pending']));`));
    check(posts.length === 1 && posts[0].post_content === body, 'Post remains pending and preserves backslashes');
    const invalidUpload = await context.request.post(base + '/wp-admin/admin-ajax.php', { multipart: { action: 'vuonlen_submit_post', nonce: browserNonce, post_title: 'Bad image test', post_content: body, post_image: { name: 'bad.php', mimeType: 'application/x-httpd-php', buffer: Buffer.from('<?php echo 1;') } } });
    check(invalidUpload.status() === 400, 'Invalid image rejected');
    check(Number(php(`echo count(get_posts(['author'=>${user.id},'post_status'=>'pending']));`)) === 1, 'Failed upload leaves no extra pending post');
    check(errors.length === 0, 'No browser JavaScript exceptions');
  } finally {
    if (browser) await browser.close();
    if (user) php(`require_once ABSPATH.'wp-admin/includes/user.php';foreach(get_posts(['author'=>${user.id},'post_type'=>['post','riseup_contact','attachment'],'post_status'=>'any','numberposts'=>-1]) as $p){wp_delete_post($p->ID,true);}wp_delete_user(${user.id});`);
    console.log('Test data removed. No email sent.');
  }
})().catch(error => { console.error(error.message); process.exitCode = 1; });
