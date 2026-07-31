<?php

declare(strict_types=1);

/**
 * Local-only blog admin auth.
 *
 * Default password for local testing: bitmixlist-local
 * Override with BITMIXLIST_BLOG_ADMIN_PASSWORD env or admin-password.hash file.
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
    ]);
    session_start();
}

function blog_admin_password_hash(): string
{
    $env = getenv('BITMIXLIST_BLOG_ADMIN_PASSWORD');
    if (is_string($env) && $env !== '') {
        return password_hash($env, PASSWORD_DEFAULT);
    }

    $hashFile = __DIR__ . '/admin-password.hash';
    if (is_file($hashFile)) {
        $hash = trim((string) file_get_contents($hashFile));
        if ($hash !== '') {
            return $hash;
        }
    }

    // Default local password: bitmixlist-local
    return password_hash('bitmixlist-local', PASSWORD_DEFAULT);
}

function blog_admin_verify_password(string $password): bool
{
    $hashFile = __DIR__ . '/admin-password.hash';
    if (is_file($hashFile)) {
        $hash = trim((string) file_get_contents($hashFile));
        // Production: hash file is authoritative; never fall back to the local default.
        return $hash !== '' && password_verify($password, $hash);
    }

    $env = getenv('BITMIXLIST_BLOG_ADMIN_PASSWORD');
    if (is_string($env) && $env !== '') {
        return hash_equals($env, $password);
    }

    // Local-only default when neither hash nor env is configured.
    return hash_equals('bitmixlist-local', $password);
}

function blog_admin_require_login(): void
{
    blog_admin_session_start();
    if (!empty($_SESSION['blog_admin_ok'])) {
        return;
    }
    header('Location: login.php');
    exit;
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
