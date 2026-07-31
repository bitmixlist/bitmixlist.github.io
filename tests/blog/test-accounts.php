#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * Account flow tests against an isolated users.json path.
 */

$root = dirname(__DIR__, 2);
$adminBlog = $root . '/admin/blog';
$tmp = sys_get_temp_dir() . '/bitmixlist-blog-accounts-' . bin2hex(random_bytes(4));
mkdir($tmp, 0700, true);
$accountsFile = $tmp . '/users.json';
putenv('BITMIXLIST_BLOG_ACCOUNTS_PATH=' . $accountsFile);

require_once $adminBlog . '/accounts.php';

$failed = 0;
$passed = 0;
function t(bool $ok, string $msg): void
{
    global $failed, $passed;
    if ($ok) {
        echo "PASS  {$msg}\n";
        $passed++;
    } else {
        echo "FAIL  {$msg}\n";
        $failed++;
    }
}

file_put_contents($accountsFile, json_encode([
    'updated_at' => gmdate('c'),
    'users' => [[
        'username' => 'notatether',
        'role' => 'admin',
        'status' => 'active',
        'password_hash' => password_hash('AdminPass12345', PASSWORD_DEFAULT),
        'created_at' => gmdate('c'),
        'approved_at' => gmdate('c'),
        'approved_by' => 'system',
        'disabled_at' => null,
        'password_set_at' => gmdate('c'),
    ]],
], JSON_PRETTY_PRINT) . "\n");

$reg = blog_accounts_register('neweditor');
t($reg['ok'] === true, 'register editor');
$user = blog_accounts_find('neweditor');
t(($user['status'] ?? '') === 'pending', 'new editor is pending');
t(($user['role'] ?? '') === 'editor', 'new editor role is editor');
t(empty($user['password_hash']), 'pending has no password');

$authPending = blog_accounts_authenticate('neweditor', 'whatever12345');
t($authPending['ok'] === false, 'pending cannot login');

$setEarly = blog_accounts_set_password('neweditor', 'EditorPass12345', 'EditorPass12345');
t($setEarly['ok'] === false, 'cannot set password before approval');

$ap = blog_accounts_approve('neweditor', 'notatether');
t($ap['ok'] === true, 'admin approves editor');
$user = blog_accounts_find('neweditor');
t(($user['status'] ?? '') === 'approved', 'status approved after approve');

$set = blog_accounts_set_password('neweditor', 'EditorPass12345', 'EditorPass12345');
t($set['ok'] === true, 'set password after approval');
$user = blog_accounts_find('neweditor');
t(($user['status'] ?? '') === 'active', 'active after password set');

$login = blog_accounts_authenticate('neweditor', 'EditorPass12345');
t($login['ok'] === true, 'editor can login');

$dis = blog_accounts_disable('neweditor', 'notatether');
t($dis['ok'] === true, 'admin disables editor');
$loginDis = blog_accounts_authenticate('neweditor', 'EditorPass12345');
t($loginDis['ok'] === false, 'disabled cannot login');

$en = blog_accounts_enable('neweditor');
t($en['ok'] === true, 'enable editor');
$loginEn = blog_accounts_authenticate('neweditor', 'EditorPass12345');
t($loginEn['ok'] === true, 'enabled can login again');

$adminLogin = blog_accounts_authenticate('notatether', 'AdminPass12345');
t($adminLogin['ok'] === true, 'admin login works');

$regAdmin = blog_accounts_register('notatether');
t($regAdmin['ok'] === false, 'cannot register reserved primary username');

@unlink($accountsFile);
@rmdir($tmp);

echo "\n{$passed} passed, {$failed} failed\n";
exit($failed > 0 ? 1 : 0);
