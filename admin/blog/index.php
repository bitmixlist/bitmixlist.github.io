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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'rebuild') {
    if (!blog_admin_verify_csrf($_POST['csrf'] ?? null)) {
        $message = 'CSRF check failed.';
    } else {
        $result = blog_build($root, $config);
        blog_update_sitemap($root, $config);
        $message = 'Rebuilt ' . count($result['written']) . ' files.';
    }
}

if (isset($_GET['logout'])) {
    blog_admin_session_start();
    $_SESSION = [];
    session_destroy();
    header('Location: login.php');
    exit;
}

$csrf = blog_admin_csrf_token();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title>Blog Admin – BitMixList</title>
<style>
body { font-family: system-ui, sans-serif; background: #121018; color: #f2ecff; margin: 0; }
header { display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-bottom: 1px solid #3a2e55; }
main { padding: 20px; max-width: 960px; margin: 0 auto; }
a { color: #c7b8ff; }
table { width: 100%; border-collapse: collapse; }
th, td { text-align: left; padding: 10px 8px; border-bottom: 1px solid #2d2440; vertical-align: top; }
.badge { display: inline-block; padding: 2px 8px; border-radius: 999px; font-size: 0.75rem; }
.badge-published { background: #1e3d2a; color: #b8f7c8; }
.badge-draft { background: #3d3210; color: #ffe4a3; }
.actions { display: flex; gap: 10px; flex-wrap: wrap; margin: 12px 0 20px; }
.btn { display: inline-block; padding: 8px 12px; border-radius: 8px; background: #7a61f6; color: #fff; text-decoration: none; border: 0; cursor: pointer; font: inherit; }
.btn.secondary { background: #2d2440; }
.msg { padding: 10px 12px; border-radius: 8px; background: #1a1230; border: 1px solid #3a2e55; margin-bottom: 16px; }
</style>
</head>
<body>
<header>
<strong>BitMixList Blog Admin</strong>
<a href="?logout=1">Log out</a>
</header>
<main>
<?php if ($message !== ''): ?><div class="msg"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
<div class="actions">
<a class="btn" href="edit.php">New post</a>
<form method="post" style="display:inline">
<input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>"/>
<input type="hidden" name="action" value="rebuild"/>
<button class="btn secondary" type="submit">Rebuild public pages</button>
</form>
</div>
<p>Content store: <code>src/blog/posts/</code> · Public blog base: <code><?= htmlspecialchars($config['blog_base_url'], ENT_QUOTES, 'UTF-8') ?></code></p>
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
