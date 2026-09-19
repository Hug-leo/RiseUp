// Run: node scripts/test_feedback.cjs [path-to-playwright]
// Uses local WordPress; creates and removes only its own test users/feedback.
const assert = require('node:assert/strict');
const { execFileSync } = require('node:child_process');
const path = require('node:path');
const { chromium } = require(process.argv[2] || 'playwright');
const root = path.resolve(__dirname, '..').replaceAll('\\', '/');
const php = code => execFileSync(process.env.PHP_BINARY || 'C:/xampp/php/php.exe', ['-r',
  `require '${root}/wordpress/wp-load.php';${code}`], { encoding: 'utf8' });
const users = [];
let browser;
const check = (condition, label) => { assert.ok(condition, label); console.log('PASS: ' + label); };

(async () => {
  try {
    browser = await chromium.launch({ headless: true });
    const base = php('echo home_url();');
    const endpoint = base + '/wp-admin/admin-post.php';
    const feedback = base + '/gui-y-kien/';
    const guest = await browser.newContext();
    const guestReply = await guest.request.post(endpoint, { form: { action: 'charity_submit_feedback' }, maxRedirects: 0 });
    check(guestReply.status() === 302 && guestReply.headers().location.includes('dang-nhap-thanh-vien'), 'Guest redirected to login');
    await guest.close();
    let memberPost;
    for (const role of ['riseup_member', 'subscriber', 'riseup_collaborator', 'administrator']) {
      const user = JSON.parse(php(`$id=wp_insert_user(['user_login'=>'feedback_test_'.wp_generate_password(12,false),'user_pass'=>wp_generate_password(32),'role'=>'${role}']);if(is_wp_error($id)){exit(1);}echo json_encode(['id'=>$id,'cookies'=>[['name'=>LOGGED_IN_COOKIE,'value'=>wp_generate_auth_cookie($id,time()+600,'logged_in')],['name'=>AUTH_COOKIE,'value'=>wp_generate_auth_cookie($id,time()+600,'auth')]]]);`));
      users.push(user.id);
      const context = await browser.newContext();
      await context.addCookies(user.cookies.map(cookie => ({ ...cookie, domain: new URL(base).hostname, path: '/', httpOnly: true })));
      const page = await context.newPage();
      await page.goto(feedback, { waitUntil: 'domcontentloaded' });
      const nonce = await page.locator('[name=feedback_nonce]').inputValue();
      const form = { action: 'charity_submit_feedback', feedback_nonce: nonce, feedback_subject: 'Feedback regression test', feedback_message: "Ý kiến O'Reilly: C:\\notes\\new" };
      if (role === 'riseup_member') {
        for (const change of [{ feedback_subject: ' ' }, { feedback_message: 'x'.repeat(5001) }, { feedback_subject: undefined, 'feedback_subject[]': 'bad' }]) {
          const invalid = { ...form, ...change };
          if (invalid.feedback_subject === undefined) delete invalid.feedback_subject;
          const response = await context.request.post(endpoint, { form: invalid, maxRedirects: 0 });
          check(response.status() === 303 && response.headers().location.includes('feedback=invalid'), 'Invalid input rejected');
        }
        const badNonce = await context.request.post(endpoint, { form: { ...form, feedback_nonce: 'bad' }, maxRedirects: 0 });
        check(badNonce.status() === 403, 'Invalid nonce rejected');
      }
      await page.locator('#feedback-subject').fill(form.feedback_subject);
      await page.locator('#feedback-message').fill(form.feedback_message);
      await page.locator('.feedback-form button').click();
      await page.waitForURL(/feedback=success/);
      await page.reload({ waitUntil: 'domcontentloaded' });
      const records = JSON.parse(php(`echo json_encode(get_posts(['post_type'=>'riseup_feedback','post_status'=>'private','author'=>${user.id},'numberposts'=>-1]));`));
      check(records.length === 1 && records[0].post_content === form.feedback_message, role + ': saved once, exact content preserved');
      if (!memberPost) memberPost = records[0].ID;
      const repeat = await context.request.post(endpoint, { form, maxRedirects: 0 });
      check(repeat.status() === 303 && repeat.headers().location.includes('feedback=rate'), role + ': repeat blocked');
      const inboxResponse = await page.goto(base + '/wp-admin/edit.php?post_type=riseup_feedback', { waitUntil: 'domcontentloaded' });
      if (['riseup_member', 'subscriber'].includes(role)) {
        check(page.url().includes('/tai-khoan/') || inboxResponse.status() === 403, role + ': inbox access blocked (' + inboxResponse.status() + ')');
      } else {
        check(await page.locator('#post-' + memberPost).count() === 1, role + ': sees member feedback in inbox');
        await page.goto(base + '/wp-admin/post.php?post=' + memberPost + '&action=edit', { waitUntil: 'domcontentloaded' });
        check(await page.locator('#title').inputValue() === form.feedback_subject, role + ': can open member feedback');
      }
      await context.close();
    }
  } finally {
    if (browser) await browser.close();
    if (users.length) php(`require_once ABSPATH.'wp-admin/includes/user.php';foreach([${users.join(',')}] as $id){foreach(get_posts(['post_type'=>'riseup_feedback','post_status'=>'any','author'=>$id,'numberposts'=>-1]) as $p){wp_delete_post($p->ID,true);}delete_transient('charity_feedback_'.$id);wp_delete_user($id);}`);
    console.log('Test fixtures removed. No email sent.');
  }
})().catch(error => { console.error(error.message); process.exitCode = 1; });
