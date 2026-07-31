<?php

declare(strict_types=1);

require_once __DIR__ . '/accounts.php';

/**
 * ACL helpers for blog posts.
 *
 * - Admins can always create posts and edit any post.
 * - Editors need account flag can_create to make new posts.
 * - Editors may edit a post only if listed in post editors ACL (or are created_by).
 */

/**
 * @param array<string, mixed>|null $user
 */
function blog_acl_is_admin(?array $user): bool
{
    return $user !== null
        && ($user['status'] ?? '') === 'active'
        && ($user['role'] ?? '') === 'admin';
}

/**
 * @param array<string, mixed>|null $user
 */
function blog_acl_can_create(?array $user): bool
{
    if ($user === null || ($user['status'] ?? '') !== 'active') {
        return false;
    }
    if (($user['role'] ?? '') === 'admin') {
        return true;
    }

    return !empty($user['can_create']);
}

/**
 * Normalize editors list from post frontmatter / form.
 *
 * @param mixed $raw
 * @return list<string>
 */
function blog_acl_normalize_editors($raw): array
{
    $out = [];
    if (is_string($raw)) {
        $parts = preg_split('/[\s,]+/', $raw) ?: [];
        foreach ($parts as $p) {
            $u = blog_accounts_normalize_username($p);
            if ($u !== '') {
                $out[] = $u;
            }
        }
    } elseif (is_array($raw)) {
        foreach ($raw as $p) {
            $u = blog_accounts_normalize_username((string) $p);
            if ($u !== '') {
                $out[] = $u;
            }
        }
    }

    return array_values(array_unique($out));
}

/**
 * @param array<string, mixed> $post
 * @return list<string>
 */
function blog_acl_post_editors(array $post): array
{
    $editors = blog_acl_normalize_editors($post['editors'] ?? []);
    $createdBy = blog_accounts_normalize_username((string) ($post['created_by'] ?? ''));
    if ($createdBy !== '' && !in_array($createdBy, $editors, true)) {
        $editors[] = $createdBy;
    }

    return $editors;
}

/**
 * @param array<string, mixed>|null $user
 * @param array<string, mixed>|null $post null = new post
 */
function blog_acl_can_edit(?array $user, ?array $post): bool
{
    if ($user === null || ($user['status'] ?? '') !== 'active') {
        return false;
    }
    if (($user['role'] ?? '') === 'admin') {
        return true;
    }
    if ($post === null) {
        // Creating a new post
        return blog_acl_can_create($user);
    }

    $username = blog_accounts_normalize_username((string) ($user['username'] ?? ''));
    if ($username === '') {
        return false;
    }

    return in_array($username, blog_acl_post_editors($post), true);
}

/**
 * Active users eligible to be granted edit rights (active editors + admins).
 *
 * @return list<array<string, mixed>>
 */
function blog_acl_eligible_users(): array
{
    $out = [];
    foreach (blog_accounts_all() as $user) {
        if (($user['status'] ?? '') !== 'active') {
            continue;
        }
        $out[] = $user;
    }

    return $out;
}

/**
 * @return array{ok: bool, error?: string}
 */
function blog_accounts_set_can_create(string $username, bool $canCreate, string $actorUsername): array
{
    $user = blog_accounts_find($username);
    if ($user === null) {
        return ['ok' => false, 'error' => 'User not found.'];
    }
    if (($user['role'] ?? '') === 'admin') {
        return ['ok' => false, 'error' => 'Admins always may create posts.'];
    }
    $actor = blog_accounts_find($actorUsername);
    if ($actor === null || ($actor['role'] ?? '') !== 'admin') {
        return ['ok' => false, 'error' => 'Only admins can change create permission.'];
    }

    $user['can_create'] = $canCreate;
    blog_accounts_upsert($user);

    return ['ok' => true];
}
