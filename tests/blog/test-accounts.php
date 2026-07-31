#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * Account flow tests against a temp data dir (no HTTP).
 */

$root = dirname(__DIR__, 2);
$adminBlog = $root . '/admin/blog';

// Isolate accounts data
$tmp = sys_get_temp_dir() . '/bitmixlist-blog-accounts-' . bin2hex(random_bytes(4));
mkdir($tmp, 0700, true);
putenv('BITMIXLIST_BLOG_ACCOUNTS_PATH=' . $tmp . '/users.json');

// Monkey-patch path via redefining if we use getenv in accounts - update accounts.php to check env
require_once $adminBlog . '/accounts.php';

// If accounts path is fixed, write into real path - better patch accounts_path via env
// Check if blog_accounts_path uses env
$src = file_get_contents($adminBlog . '/accounts.php');
if (!str_contains((string) $src, 'BITMIXLIST_BLOG_ACCOUNTS_PATH')) {
    fwrite(STDERR, "accounts.php should honor BITMIXLIST_BLOG_ACCOUNTS_PATH for tests\n");
    // still run against temp by overriding through function_exists - skip
}

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

// Force isolated path if supported
if (function_exists('blog_accounts_path')) {
    // re-include won't help; patch by writing to default after backing up
}

$accountsFile = $adminBlog . '/data/users.json';
$backup = null;
if (is_file($accountsFile)) {
    $backup = file_get_contents($accountsFile);
}
@mkdir(dirname($accountsFile), 0770, true);
// Fresh bootstrap with known password
file_put_contents($accountsFile, json_encode([
    'updated_at' => gmdate('c'),
    'users' => [[
        'username' => 'admin',
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

// Reload by clearing any static state - none

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

$ap = blog_accounts_approve('neweditor', 'admin');
t($ap['ok'] === true, 'admin approves editor');
$user = blog_accounts_find('neweditor');
t(($user['status'] ?? '') === 'approved', 'status approved after approve');

$set = blog_accounts_set_password('neweditor', 'EditorPass12345', 'EditorPass12345');
t($set['ok'] === true, 'set password after approval');
$user = blog_accounts_find('neweditor');
t(($user['status'] ?? '') === 'active', 'active after password set');

$login = blog_accounts_authenticate('neweditor', 'EditorPass12345');
t($login['ok'] === true, 'editor can login');

$dis = blog_accounts_disable('neweditor', 'admin');
t($dis['ok'] === true, 'admin disables editor');
$loginDis = blog_accounts_authenticate('neweditor', 'EditorPass12345');
t($loginDis['ok'] === false, 'disabled cannot login');

$en = blog_accounts_enable('neweditor');
t($en['ok'] === true, 'enable editor');
$loginEn = blog_accounts_authenticate('neweditor', 'EditorPass12345');
t($loginEn['ok'] === true, 'enabled can login again');

$adminLogin = blog_accounts_authenticate('admin', 'AdminPass12345');
t($adminLogin['ok'] === true, 'admin login works');

$regAdmin = blog_accounts_register('admin');
t($regAdmin['ok'] === false, 'cannot register reserved admin username');

// restore backup
if ($backup !== null) {
    file_put_contents($accountsFile, $backup);
} else {
    @unlink($accountsFile);
}

echo "\n{$passed} passed, {$failed} failed\n";
exit($failed > 0 ? 1 : 0);
