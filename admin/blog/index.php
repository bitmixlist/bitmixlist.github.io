<?php

declare(strict_types=1);

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../../src/blog/store.php';
require_once __DIR__ . '/../../src/blog/publish.php';

blog_admin_require_login();

$root = dirname(__DIR__, 2);
$config = blog_config();
$posts = blog_load_all_posts($root, $config);
$message = '';
$error = '';
$user = blog_admin_current_user();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'rebuild') {
    if (!blog_admin_verify_csrf($_POST['csrf'] ?? null)) {
        $error = 'CSRF check failed.';
    } else {
        $result = blog_build($root, $config);
        blog_update_sitemap($root, $config);
        $message = 'Rebuilt ' . count($result['written']) . ' files.';
    }
}

$csrf = blog_admin_csrf_token();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title>Posts – BitMixList Blog</title>
<style><?= blog_admin_layout_styles() ?>
.badge-published { background: #1e3d2a; color: #b8f7c8; }
.badge-draft { background: #3d3210; color: #ffe4a3; }
</style>
</head>
<body>
<?= blog_admin_header_html('Posts') ?>
<main>
<?php if ($message !== ''): ?><div class="msg"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
<?php if ($error !== ''): ?><div class="msg err"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
<div class="actions">
<a class="btn" href="edit.php">New post</a>
<form method="post" style="display:inline">
<input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>"/>
<input type="hidden" name="action" value="rebuild"/>
<button class="btn secondary" type="submit">Rebuild public pages</button>
</form>
<?php if (blog_admin_is_admin()): ?>
<a class="btn secondary" href="users.php">Manage users</a>
<?php endif; ?>
</div>
<p class="muted">Signed in as <strong><?= htmlspecialchars((string) ($user['username'] ?? ''), ENT_QUOTES, 'UTF-8') ?></strong>
(<?= htmlspecialchars((string) ($user['role'] ?? ''), ENT_QUOTES, 'UTF-8') ?>).
Public: <code><?= htmlspecialchars($config['site_base_url'], ENT_QUOTES, 'UTF-8') ?>/blog/</code></p>
<table>
<thead>
<tr><th>Slug</th><th>Title (EN)</th><th>Status</th><th>Published</th><th></th></tr>
</thead>
<tbody>
<?php if ($posts === []): ?>
<tr><td colspan="5">No posts yet.</td></tr>
<?php endif; ?>
<?php foreach ($posts as $post): ?>
<tr>
<td><code><?= htmlspecialchars($post['slug'], ENT_QUOTES, 'UTF-8') ?></code></td>
<td><?= htmlspecialchars((string) ($post['locales']['en']['title'] ?: $post['slug']), ENT_QUOTES, 'UTF-8') ?></td>
<td><span class="badge badge-<?= htmlspecialchars($post['status'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($post['status'], ENT_QUOTES, 'UTF-8') ?></span></td>
<td><?= htmlspecialchars(substr((string) $post['published_at'], 0, 10), ENT_QUOTES, 'UTF-8') ?></td>
<td><a href="edit.php?slug=<?= urlencode($post['slug']) ?>">Edit</a></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</main>
</body>
</html>
