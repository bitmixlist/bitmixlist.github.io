#!/usr/bin/env php
<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$tmp = sys_get_temp_dir() . '/bitmixlist-blog-acl-' . bin2hex(random_bytes(4));
mkdir($tmp, 0700, true);
putenv('BITMIXLIST_BLOG_ACCOUNTS_PATH=' . $tmp . '/users.json');

require_once $root . '/admin/blog/accounts.php';
require_once $root . '/admin/blog/acl.php';

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

file_put_contents($tmp . '/users.json', json_encode([
    'updated_at' => gmdate('c'),
    'users' => [
        [
            'username' => 'notatether',
            'role' => 'admin',
            'status' => 'active',
            'can_create' => true,
            'password_hash' => password_hash('x', PASSWORD_DEFAULT),
            'created_at' => gmdate('c'),
        ],
        [
            'username' => 'alice',
            'role' => 'editor',
            'status' => 'active',
            'can_create' => false,
            'password_hash' => password_hash('x', PASSWORD_DEFAULT),
            'created_at' => gmdate('c'),
        ],
        [
            'username' => 'bob',
            'role' => 'editor',
            'status' => 'active',
            'can_create' => true,
            'password_hash' => password_hash('x', PASSWORD_DEFAULT),
            'created_at' => gmdate('c'),
        ],
    ],
], JSON_PRETTY_PRINT) . "\n");

$admin = blog_accounts_find('notatether');
$alice = blog_accounts_find('alice');
$bob = blog_accounts_find('bob');

t(blog_acl_can_create($admin), 'admin can create');
t(!blog_acl_can_create($alice), 'alice cannot create by default');
t(blog_acl_can_create($bob), 'bob can create');

$post = [
    'slug' => 'demo',
    'created_by' => 'bob',
    'editors' => ['bob'],
];

t(blog_acl_can_edit($admin, $post), 'admin can edit any post');
t(blog_acl_can_edit($bob, $post), 'bob can edit own post');
t(!blog_acl_can_edit($alice, $post), 'alice cannot edit bob post');

$post['editors'] = ['bob', 'alice'];
t(blog_acl_can_edit($alice, $post), 'alice can edit when on ACL');

t(!blog_acl_can_edit($alice, null), 'alice cannot open new post form');
t(blog_acl_can_edit($bob, null), 'bob can open new post form');

$grant = blog_accounts_set_can_create('alice', true, 'notatether');
t($grant['ok'] === true, 'admin grants create to alice');
$alice = blog_accounts_find('alice');
t(blog_acl_can_create($alice), 'alice can create after grant');

$deny = blog_accounts_set_can_create('alice', false, 'notatether');
t($deny['ok'] === true, 'admin revokes create');
$alice = blog_accounts_find('alice');
t(!blog_acl_can_create($alice), 'alice cannot create after revoke');

// empty editors: only admin
$orphan = ['slug' => 'legacy', 'editors' => [], 'created_by' => ''];
t(blog_acl_can_edit($admin, $orphan), 'admin edits legacy with empty ACL');
t(!blog_acl_can_edit($bob, $orphan), 'editor cannot edit empty ACL post');

@unlink($tmp . '/users.json');
@rmdir($tmp);

echo "\n{$passed} passed, {$failed} failed\n";
exit($failed > 0 ? 1 : 0);
