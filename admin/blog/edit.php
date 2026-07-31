<?php

declare(strict_types=1);

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../../src/blog/store.php';
require_once __DIR__ . '/../../src/blog/publish.php';

blog_admin_require_login();

$root = dirname(__DIR__, 2);
$config = blog_config();
$user = blog_admin_current_user();
$username = blog_accounts_normalize_username((string) ($user['username'] ?? ''));
$slug = isset($_GET['slug']) ? blog_normalize_slug((string) $_GET['slug']) : '';
$post = $slug !== '' ? blog_load_post($root, $slug, $config) : null;
$isNew = $post === null;
$error = '';
$notice = '';

if ($isNew && !blog_acl_can_create($user)) {
    http_response_code(403);
    echo 'You are not allowed to create new posts.';
    exit;
}
if (!$isNew && !blog_acl_can_edit($user, $post)) {
    http_response_code(403);
    echo 'You are not allowed to edit this post.';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!blog_admin_verify_csrf($_POST['csrf'] ?? null)) {
        $error = 'CSRF check failed.';
    } else {
        $action = (string) ($_POST['action'] ?? 'save');
        $postSlug = blog_normalize_slug((string) ($_POST['slug'] ?? ''));
        $existingForAcl = $postSlug !== '' ? blog_load_post($root, $postSlug, $config) : null;
        $creating = $existingForAcl === null;

        if ($creating && !blog_acl_can_create($user)) {
            $error = 'You are not allowed to create new posts.';
        } elseif (!$creating && !blog_acl_can_edit($user, $existingForAcl)) {
            $error = 'You are not allowed to edit this post.';
        } else {
            $editors = [];
            if (blog_acl_is_admin($user) && isset($_POST['editors']) && is_array($_POST['editors'])) {
                $editors = blog_acl_normalize_editors($_POST['editors']);
            } elseif ($existingForAcl !== null) {
                $editors = blog_acl_post_editors($existingForAcl);
            } else {
                $editors = [$username];
            }
            if ($username !== '' && !in_array($username, $editors, true) && !blog_acl_is_admin($user)) {
                $editors[] = $username;
            }
            if ($creating && $username !== '' && !in_array($username, $editors, true)) {
                $editors[] = $username;
            }

            $fields = [
                'slug' => (string) ($_POST['slug'] ?? ''),
                'title_en' => (string) ($_POST['title_en'] ?? ''),
                'title_ru' => (string) ($_POST['title_ru'] ?? ''),
                'description_en' => (string) ($_POST['description_en'] ?? ''),
                'description_ru' => (string) ($_POST['description_ru'] ?? ''),
                'body_en' => (string) ($_POST['body_en'] ?? ''),
                'body_ru' => (string) ($_POST['body_ru'] ?? ''),
                'body_format' => (string) ($_POST['body_format'] ?? 'markdown'),
                'tags' => (string) ($_POST['tags'] ?? ''),
                'published_at' => (string) ($_POST['published_at'] ?? ''),
                'canonical_path' => (string) ($_POST['canonical_path'] ?? ''),
                'author' => (string) ($_POST['author'] ?? $config['default_author']),
                'status' => $action === 'publish' ? 'published' : (string) ($_POST['status'] ?? 'draft'),
                'editors' => $editors,
                'created_by' => $creating
                    ? $username
                    : (string) ($existingForAcl['created_by'] ?? $username),
            ];
            if ($action === 'publish') {
                $fields['status'] = 'published';
            } elseif ($action === 'draft') {
                $fields['status'] = 'draft';
            }

            try {
                $result = blog_save_from_fields($root, $fields, true, $config);
                blog_update_sitemap($root, $config);
                $slug = blog_normalize_slug($fields['slug'] !== '' ? $fields['slug'] : (string) ($fields['title_en'] ?? 'untitled'));
                $post = blog_load_post($root, $slug, $config);
                $isNew = false;
                $written = isset($result['build']['written']) ? count($result['build']['written']) : 0;
                $notice = ($fields['status'] === 'published' ? 'Published' : 'Saved draft') . " · rebuilt {$written} public file(s).";
            } catch (Throwable $e) {
                $error = $e->getMessage();
            }
        }
    }
}

$csrf = blog_admin_csrf_token();
$p = $post ?? [
    'slug' => '',
    'status' => 'draft',
    'published_at' => gmdate('Y-m-d\TH:i:s\Z'),
    'canonical_path' => '',
    'body_format' => 'markdown',
    'author' => $config['default_author'],
    'tags' => [],
    'created_by' => $username,
    'editors' => $username !== '' ? [$username] : [],
    'locales' => [
        'en' => ['title' => '', 'description' => '', 'body' => ''],
        'ru' => ['title' => '', 'description' => '', 'body' => ''],
    ],
];
$tags = implode(', ', $p['tags'] ?? []);
$selectedEditors = blog_acl_post_editors($p);
$eligible = blog_acl_eligible_users();
$isAdmin = blog_acl_is_admin($user);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title><?= $post ? 'Edit' : 'New' ?> post – BitMixList Blog</title>
<style><?= blog_admin_layout_styles() ?>
textarea { min-height: 220px; font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; font-size: 0.9rem; line-height: 1.45; }
.grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.acl-box { border: 1px solid #3a2e55; border-radius: 8px; padding: 12px; background: #0f0d16; }
.acl-box label.row { display: flex; align-items: center; gap: 8px; margin: 6px 0; color: #e8e1f5; font-size: 0.92rem; }
.acl-box input[type=checkbox] { width: auto; }
@media (max-width: 700px) { .grid { grid-template-columns: 1fr; } }
</style>
</head>
<body>
<?= blog_admin_header_html($post ? 'Edit post' : 'New post') ?>
<main>
<?php if ($notice !== ''): ?><div class="msg"><?= htmlspecialchars($notice, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
<?php if ($error !== ''): ?><div class="msg err"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
<form method="post">
<input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>"/>
<input type="hidden" name="status" value="<?= htmlspecialchars((string) $p['status'], ENT_QUOTES, 'UTF-8') ?>"/>

<div class="grid">
<div>
<label for="slug">Slug</label>
<input id="slug" name="slug" required value="<?= htmlspecialchars((string) $p['slug'], ENT_QUOTES, 'UTF-8') ?>" placeholder="my-post-slug"/>
</div>
<div>
<label for="canonical_path">Canonical path (public file)</label>
<input id="canonical_path" name="canonical_path" value="<?= htmlspecialchars((string) ($p['canonical_path'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="blog/my-post.html or legacy.html"/>
</div>
</div>

<div class="grid">
<div>
<label for="published_at">Published at (ISO)</label>
<input id="published_at" name="published_at" value="<?= htmlspecialchars((string) $p['published_at'], ENT_QUOTES, 'UTF-8') ?>"/>
</div>
<div>
<label for="body_format">Body format</label>
<select id="body_format" name="body_format">
<option value="markdown" <?= ($p['body_format'] ?? '') === 'markdown' ? 'selected' : '' ?>>markdown</option>
<option value="html" <?= ($p['body_format'] ?? '') === 'html' ? 'selected' : '' ?>>html</option>
</select>
</div>
</div>

<div class="grid">
<div>
<label for="author">Author</label>
<input id="author" name="author" value="<?= htmlspecialchars((string) $p['author'], ENT_QUOTES, 'UTF-8') ?>"/>
</div>
<div>
<label for="tags">Tags (comma-separated)</label>
<input id="tags" name="tags" value="<?= htmlspecialchars($tags, ENT_QUOTES, 'UTF-8') ?>"/>
</div>
</div>

<label>Who can edit this post</label>
<div class="acl-box">
<?php if ($isAdmin): ?>
<p class="muted" style="margin:0 0 8px">Admins can always edit. Check accounts that may edit this post.</p>
<?php foreach ($eligible as $eu): ?>
<?php
    $euName = (string) ($eu['username'] ?? '');
    $checked = in_array($euName, $selectedEditors, true) || ($eu['role'] ?? '') === 'admin' && $euName === $username;
    // Always show checked for selected; admin accounts can still be listed
?>
<label class="row">
<input type="checkbox" name="editors[]" value="<?= htmlspecialchars($euName, ENT_QUOTES, 'UTF-8') ?>" <?= in_array($euName, $selectedEditors, true) ? 'checked' : '' ?>/>
<code><?= htmlspecialchars($euName, ENT_QUOTES, 'UTF-8') ?></code>
<span class="muted">(<?= htmlspecialchars((string) ($eu['role'] ?? 'editor'), ENT_QUOTES, 'UTF-8') ?>)</span>
</label>
<?php endforeach; ?>
<?php if ($eligible === []): ?>
<p class="muted">No active accounts yet.</p>
<?php endif; ?>
<?php else: ?>
<p class="muted" style="margin:0">Editors: <?= $selectedEditors === [] ? 'admin only' : htmlspecialchars(implode(', ', $selectedEditors), ENT_QUOTES, 'UTF-8') ?>. Only an admin can change ACL.</p>
<?php endif; ?>
</div>

<label for="title_en">Title (EN)</label>
<input id="title_en" name="title_en" value="<?= htmlspecialchars((string) ($p['locales']['en']['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"/>

<label for="description_en">Description (EN)</label>
<input id="description_en" name="description_en" value="<?= htmlspecialchars((string) ($p['locales']['en']['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"/>

<label for="body_en">Body (EN)</label>
<textarea id="body_en" name="body_en"><?= htmlspecialchars((string) ($p['locales']['en']['body'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>

<label for="title_ru">Title (RU)</label>
<input id="title_ru" name="title_ru" value="<?= htmlspecialchars((string) ($p['locales']['ru']['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"/>

<label for="description_ru">Description (RU)</label>
<input id="description_ru" name="description_ru" value="<?= htmlspecialchars((string) ($p['locales']['ru']['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"/>

<label for="body_ru">Body (RU)</label>
<textarea id="body_ru" name="body_ru"><?= htmlspecialchars((string) ($p['locales']['ru']['body'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>

<div class="actions">
<button class="btn secondary" type="submit" name="action" value="draft">Save draft</button>
<button class="btn" type="submit" name="action" value="publish">Publish</button>
<?php if (!empty($p['slug'])): ?>
<a class="btn secondary" href="../../<?= htmlspecialchars(ltrim((string) ($p['canonical_path'] ?: ('blog/' . $p['slug'] . '.html')), '/'), ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">View public file</a>
<?php endif; ?>
</div>
</form>
</main>
</body>
</html>
