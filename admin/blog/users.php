<?php

declare(strict_types=1);

require_once __DIR__ . '/auth.php';

blog_admin_require_admin();

$actor = blog_admin_current_user();
$actorName = (string) ($actor['username'] ?? 'admin');
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!blog_admin_verify_csrf($_POST['csrf'] ?? null)) {
        $error = 'CSRF check failed.';
    } else {
        $action = (string) ($_POST['action'] ?? '');
        $username = (string) ($_POST['username'] ?? '');
        $result = match ($action) {
            'approve' => blog_accounts_approve($username, $actorName),
            'disable' => blog_accounts_disable($username, $actorName),
            'enable' => blog_accounts_enable($username),
            'reset_password' => blog_accounts_reset_password($username),
            default => ['ok' => false, 'error' => 'Unknown action.'],
        };
        if ($result['ok']) {
            $message = match ($action) {
                'approve' => "Approved {$username}. They can set a password now.",
                'disable' => "Disabled {$username}.",
                'enable' => "Enabled {$username}.",
                'reset_password' => "Password reset for {$username}. They must set a new password.",
                default => 'Done.',
            };
        } else {
            $error = (string) ($result['error'] ?? 'Action failed.');
        }
    }
}

$csrf = blog_admin_csrf_token();
$users = blog_accounts_all();

function blog_users_badge(string $status): string
{
    $class = match ($status) {
        'pending' => 'badge-pending',
        'approved' => 'badge-approved',
        'active' => 'badge-active',
        'disabled' => 'badge-disabled',
        default => '',
    };

    return '<span class="badge ' . htmlspecialchars($class, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($status, ENT_QUOTES, 'UTF-8') . '</span>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title>Users – BitMixList Blog</title>
<style><?= blog_admin_layout_styles() ?></style>
</head>
<body>
<?= blog_admin_header_html('Users') ?>
<main>
<?php if ($message !== ''): ?><div class="msg"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
<?php if ($error !== ''): ?><div class="msg err"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
<p class="muted">Register form creates <strong>editor</strong> accounts only. Approve by username so they can set a password. Disable blocks sign-in.</p>
<table>
<thead>
<tr><th>Username</th><th>Role</th><th>Status</th><th>Created</th><th>Actions</th></tr>
</thead>
<tbody>
<?php foreach ($users as $user): ?>
<?php
    $u = (string) ($user['username'] ?? '');
    $role = (string) ($user['role'] ?? 'editor');
    $status = (string) ($user['status'] ?? '');
    $created = substr((string) ($user['created_at'] ?? ''), 0, 10);
?>
<tr>
<td><code><?= htmlspecialchars($u, ENT_QUOTES, 'UTF-8') ?></code></td>
<td><span class="badge <?= $role === 'admin' ? 'badge-admin' : 'badge-editor' ?>"><?= htmlspecialchars($role, ENT_QUOTES, 'UTF-8') ?></span></td>
<td><?= blog_users_badge($status) ?></td>
<td><?= htmlspecialchars($created, ENT_QUOTES, 'UTF-8') ?></td>
<td>
<div class="actions" style="margin:0">
<?php if ($status === 'pending'): ?>
<form method="post" style="display:inline">
<input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>"/>
<input type="hidden" name="username" value="<?= htmlspecialchars($u, ENT_QUOTES, 'UTF-8') ?>"/>
<input type="hidden" name="action" value="approve"/>
<button class="btn" type="submit">Approve</button>
</form>
<?php endif; ?>
<?php if ($status === 'disabled'): ?>
<form method="post" style="display:inline">
<input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>"/>
<input type="hidden" name="username" value="<?= htmlspecialchars($u, ENT_QUOTES, 'UTF-8') ?>"/>
<input type="hidden" name="action" value="enable"/>
<button class="btn secondary" type="submit">Enable</button>
</form>
<?php elseif ($status !== 'pending' && $u !== 'admin'): ?>
<form method="post" style="display:inline">
<input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>"/>
<input type="hidden" name="username" value="<?= htmlspecialchars($u, ENT_QUOTES, 'UTF-8') ?>"/>
<input type="hidden" name="action" value="disable"/>
<button class="btn danger" type="submit">Disable</button>
</form>
<?php endif; ?>
<?php if (in_array($status, ['active', 'approved'], true) && $u !== 'admin'): ?>
<form method="post" style="display:inline">
<input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>"/>
<input type="hidden" name="username" value="<?= htmlspecialchars($u, ENT_QUOTES, 'UTF-8') ?>"/>
<input type="hidden" name="action" value="reset_password"/>
<button class="btn secondary" type="submit">Reset password</button>
</form>
<?php endif; ?>
</div>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</main>
</body>
</html>
