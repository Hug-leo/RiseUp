// node scripts/test_submissions.cjs <path-to-playwright>
const { chromium } = require(process.argv[2] || 'playwright');
const { execFileSync } = require('node:child_process');
const assert = require('node:assert/strict');
const root = require('node:path').resolve(__dirname, '..').replaceAll('\\', '/');
const php = code => execFileSync('C:/xampp/php/php.exe', ['-r', `require '${root}/wordpress/wp-load.php';${code}`], { encoding: 'utf8' });
const ok = (test, label) => { assert.ok(test, label); console.log('PASS: ' + label); };
const users = [];
let browser;
(async () => {
  try {
    browser = await chromium.launch();
    const base = php('echo home_url();');
    async function account(role) {
      const u = JSON.parse(php(`$id=wp_insert_user(['user_login'=>'submission_test_'.wp_generate_password(12,false),'user_pass'=>wp_generate_password(32),'role'=>'${role}']);echo json_encode(['id'=>$id,'cookies'=>[['name'=>LOGGED_IN_COOKIE,'value'=>wp_generate_auth_cookie($id,time()+600,'logged_in')],['name'=>AUTH_COOKIE,'value'=>wp_generate_auth_cookie($id,time()+600,'auth')]]]);`));
      users.push(u.id);
      const c = await browser.newContext();
      await c.addCookies(u.cookies.map(x => ({ ...x, domain: new URL(base).hostname, path: '/' })));
      return { ...u, c, p: await c.newPage() };
    }
    const member = await account('riseup_member');
    const other = await account('riseup_member');
    const editor = await account('riseup_collaborator');
    const admin = await account('administrator');
    await member.p.goto(base + '/gui-bai/');
    await member.p.locator('#article-title').fill('Submission integration test');
    await member.p.locator('#article-content').fill("Nội dung O'Reilly C:\\test");
    const image = Buffer.from('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+aWQAAAABJRU5ErkJggg==', 'base64');
    await member.p.locator('#article-image').setInputFiles({ name: 'test.png', mimeType: 'image/png', buffer: image });
    await member.p.locator('button[value=draft]').click();
    await member.p.waitForURL(/saved=/);
    const id = Number(new URL(member.p.url()).searchParams.get('saved'));
    ok(php(`echo get_post_status(${id});`) === 'draft', 'Member saves draft');
    ok(Number(php(`echo get_post_thumbnail_id(${id});`)) > 0, 'Member uploads real image without general media permissions');
    ok((await other.c.request.get(base + '/gui-bai/?article=' + id)).status() === 403, 'Other member cannot edit draft');
    await member.p.goto(base + '/gui-bai/?article=' + id);
    ok(await member.p.locator('#article-content').inputValue() === "Nội dung O'Reilly C:\\test", 'Draft preserves content');
    const form = await member.p.locator('form.feedback-form').evaluate(f => Object.fromEntries(new FormData(f)));
    delete form.article_image;
    form.intent = 'submit';
    await member.p.locator('button[value=submit]').click();
    await member.p.waitForURL(/saved=/);
    await member.c.request.post(base + '/gui-bai/', { form });
    ok(php(`echo count(get_posts(['author'=>${member.id},'post_status'=>'any']));`) === '1', 'Repeated request does not duplicate post');
    ok(php(`echo get_post_status(${id});`) === 'pending', 'Submission waits for review');
    ok((await member.c.request.get(base + '/gui-bai/?article=' + id)).status() === 403, 'Pending article locked for sender');
    ok(php(`wp_set_current_user(${editor.id});echo current_user_can('publish_posts')?'yes':'no';`) === 'no', 'Collaborator cannot publish');
    await editor.p.goto(base + '/wp-admin/post.php?post=' + id + '&action=edit');
    await editor.p.locator('#submission-reason').fill('Bổ sung nguồn tham khảo');
    await editor.p.locator('[data-review=changes]').click();
    await editor.p.waitForURL(/edit.php\?post_status=draft/);
    ok(php(`echo get_post_status(${id});`) === 'draft', 'Reviewer requests revisions through admin UI');
    await member.p.goto(base + '/bai-cua-toi/');
    ok((await member.p.locator('main').innerText()).includes('Bổ sung nguồn tham khảo'), 'Sender sees review reason');
    await member.p.goto(base + '/gui-bai/?article=' + id);
    await member.p.locator('#article-content').fill('Đã bổ sung nguồn');
    await member.p.locator('button[value=submit]').click();
    await member.p.waitForURL(/saved=/);
    // Publish using the real WordPress admin editor form.
    await admin.p.goto(base + '/wp-admin/post.php?post=' + id + '&action=edit');
    const takeover = admin.p.locator('#post-lock-dialog a[href*="get-post-lock"]');
    if ( await takeover.count() ) {
      await takeover.click();
      await admin.p.locator('#post-lock-dialog a[href*="get-post-lock"]').waitFor({ state: 'detached' });
    }
    await admin.p.locator('#publish').click();
    await admin.p.waitForLoadState('domcontentloaded');
    ok(php(`echo get_post_status(${id});`) === 'publish', 'Admin publishes through editor');
    ok((await member.c.request.get(base + '/gui-bai/?article=' + id)).status() === 403, 'Published article locked for sender');
    ok(php(`wp_set_current_user(${editor.id});echo current_user_can('edit_post',${id})?'yes':'no';`) === 'no', 'Collaborator cannot alter published article');
  } finally {
    if (browser) await browser.close();
    if (users.length) php(`require_once ABSPATH.'wp-admin/includes/user.php';foreach([${users.join(',')}] as $uid){foreach(get_posts(['author'=>$uid,'post_type'=>'attachment','post_status'=>'any','numberposts'=>-1]) as $p){wp_delete_attachment($p->ID,true);}foreach(get_posts(['author'=>$uid,'post_type'=>'post','post_status'=>'any','numberposts'=>-1]) as $p){wp_delete_post($p->ID,true);}wp_delete_user($uid);}`);
    console.log('Test accounts, articles and images removed.');
  }
})().catch(e => { console.error(e.message); process.exitCode = 1; });
