<?php

declare(strict_types=1);

require_once __DIR__ . '/accounts.php';
require_once __DIR__ . '/acl.php';

/**
 * Session + access control for blog admin/editor UI.
 */
function blog_admin_session_start(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    session_name('bitmixlist_blog_admin');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/admin/blog',
        'httponly' => true,
        'samesite' => 'Lax',
        'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
    ]);
    session_start();
}

/**
 * @return array<string, mixed>|null
 */
function blog_admin_current_user(): ?array
{
    blog_admin_session_start();
    $username = blog_accounts_normalize_username((string) ($_SESSION['blog_user'] ?? ''));
    if ($username === '') {
        return null;
    }
    $user = blog_accounts_find($username);
    if ($user === null) {
        return null;
    }
    if (($user['status'] ?? '') !== 'active') {
        return null;
    }

    return $user;
}

function blog_admin_require_login(): void
{
    $user = blog_admin_current_user();
    if ($user !== null) {
        return;
    }
    blog_admin_session_start();
    $_SESSION = [];
    header('Location: login.php');
    exit;
}

function blog_admin_require_admin(): void
{
    blog_admin_require_login();
    $user = blog_admin_current_user();
    if ($user !== null && ($user['role'] ?? '') === 'admin') {
        return;
    }
    http_response_code(403);
    echo 'Forbidden';
    exit;
}

function blog_admin_is_admin(): bool
{
    $user = blog_admin_current_user();

    return $user !== null && ($user['role'] ?? '') === 'admin';
}

/**
 * @param array<string, mixed> $user
 */
function blog_admin_login_user(array $user): void
{
    blog_admin_session_start();
    session_regenerate_id(true);
    $_SESSION['blog_user'] = blog_accounts_normalize_username((string) ($user['username'] ?? ''));
    $_SESSION['blog_role'] = (string) ($user['role'] ?? 'editor');
    // Legacy flag kept for any residual checks during transition.
    $_SESSION['blog_admin_ok'] = true;
    blog_admin_csrf_token();
}

function blog_admin_logout(): void
{
    blog_admin_session_start();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'] ?? '', (bool) $p['secure'], (bool) $p['httponly']);
    }
    session_destroy();
}

function blog_admin_csrf_token(): string
{
    blog_admin_session_start();
    if (empty($_SESSION['blog_csrf'])) {
        $_SESSION['blog_csrf'] = bin2hex(random_bytes(16));
    }

    return (string) $_SESSION['blog_csrf'];
}

function blog_admin_verify_csrf(?string $token): bool
{
    blog_admin_session_start();
    $expected = (string) ($_SESSION['blog_csrf'] ?? '');

    return $expected !== '' && is_string($token) && hash_equals($expected, $token);
}

function blog_admin_layout_styles(): string
{
    return 'body{font-family:system-ui,sans-serif;background:#121018;color:#f2ecff;margin:0}
header{display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid #3a2e55;gap:12px;flex-wrap:wrap}
header nav{display:flex;gap:14px;align-items:center;flex-wrap:wrap}
main{padding:20px;max-width:960px;margin:0 auto}
a{color:#c7b8ff}
.btn{display:inline-block;padding:8px 12px;border-radius:8px;background:#7a61f6;color:#fff;text-decoration:none;border:0;cursor:pointer;font:inherit}
.btn.secondary{background:#2d2440}
.btn.danger{background:#5a2a35}
.msg{padding:10px 12px;border-radius:8px;background:#1a1230;border:1px solid #3a2e55;margin-bottom:16px}
.err{border-color:#8b2f36;background:#2a1417;color:#ffc4c8}
.badge{display:inline-block;padding:2px 8px;border-radius:999px;font-size:.75rem}
.badge-pending{background:#3d3210;color:#ffe4a3}
.badge-approved{background:#1a2f4a;color:#b8d4ff}
.badge-active{background:#1e3d2a;color:#b8f7c8}
.badge-disabled{background:#3a1a1a;color:#ffc4c8}
.badge-admin{background:#3a2e55;color:#e8dfff}
.badge-editor{background:#243040;color:#cde}
table{width:100%;border-collapse:collapse}
th,td{text-align:left;padding:10px 8px;border-bottom:1px solid #2d2440;vertical-align:top}
label{display:block;margin:12px 0 6px;color:#c9c3d8;font-size:.9rem}
input,textarea,select{width:100%;box-sizing:border-box;padding:10px 12px;border-radius:8px;border:1px solid #4a3a70;background:#0f0d16;color:#fff;font:inherit}
.actions{display:flex;gap:10px;flex-wrap:wrap;margin:12px 0 20px}
.muted{color:#9b93ad;font-size:.9rem}';
}

function blog_admin_header_html(string $title = 'BitMixList Blog'): string
{
    $user = blog_admin_current_user();
    $name = $user ? (string) $user['username'] : '';
    $role = $user ? (string) $user['role'] : '';
    $usersLink = blog_admin_is_admin() ? '<a href="users.php">Users</a>' : '';
    $newLink = blog_acl_can_create($user) ? '<a href="edit.php">New post</a>' : '';

    return '<header>
<strong>' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</strong>
<nav>
<a href="index.php">Posts</a>
' . $newLink . '
' . $usersLink . '
<span class="muted">' . htmlspecialchars($name . ($role !== '' ? " ({$role})" : ''), ENT_QUOTES, 'UTF-8') . '</span>
<a href="logout.php">Log out</a>
</nav>
</header>';
}
