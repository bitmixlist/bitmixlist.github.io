<?php

declare(strict_types=1);

require_once __DIR__ . '/auth.php';

blog_admin_session_start();
$error = '';
$notice = '';
$prefill = blog_accounts_normalize_username((string) ($_GET['username'] ?? ''));

if (blog_admin_current_user() !== null) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = (string) ($_POST['username'] ?? '');
    $password = (string) ($_POST['password'] ?? '');
    $confirm = (string) ($_POST['password_confirm'] ?? '');
    $result = blog_accounts_set_password($username, $password, $confirm);
    if ($result['ok']) {
        $notice = 'Password set. You can sign in now.';
        $prefill = blog_accounts_normalize_username($username);
    } else {
        $error = (string) ($result['error'] ?? 'Unable to set password.');
        $prefill = blog_accounts_normalize_username($username);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title>Set password – BitMixList Blog</title>
<style>
body { font-family: system-ui, sans-serif; background: #121018; color: #f2ecff; margin: 0; min-height: 100vh; display: grid; place-items: center; }
form { width: min(400px, 92vw); padding: 24px; border: 1px solid #3a2e55; border-radius: 12px; background: #1a1230; }
h1 { margin: 0 0 8px; font-size: 1.25rem; }
p.lead { margin: 0 0 14px; color: #c9c3d8; font-size: 0.92rem; line-height: 1.45; }
label { display: block; margin: 12px 0 6px; font-size: 0.9rem; color: #c9c3d8; }
input { width: 100%; box-sizing: border-box; padding: 10px 12px; border-radius: 8px; border: 1px solid #4a3a70; background: #0f0d16; color: #fff; }
button { margin-top: 16px; width: 100%; padding: 10px; border: 0; border-radius: 8px; background: #7a61f6; color: #fff; font-weight: 650; cursor: pointer; }
.error { color: #ffb4b4; margin: 0 0 8px; font-size: 0.9rem; }
.ok { color: #b8f7c8; margin: 0 0 8px; font-size: 0.9rem; }
.links { margin-top: 16px; font-size: 0.9rem; }
.links a { color: #c7b8ff; }
</style>
</head>
<body>
<form method="post" action="set-password.php" autocomplete="username">
<h1>Set password</h1>
<p class="lead">Only available after an admin approves your registration. Choose a password of at least 10 characters.</p>
<?php if ($error !== ''): ?><p class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
<?php if ($notice !== ''): ?><p class="ok"><?= htmlspecialchars($notice, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
<label for="username">Username</label>
<input id="username" name="username" type="text" required autofocus value="<?= htmlspecialchars($prefill, ENT_QUOTES, 'UTF-8') ?>" autocomplete="username"/>
<label for="password">New password</label>
<input id="password" name="password" type="password" required minlength="10" autocomplete="new-password"/>
<label for="password_confirm">Confirm password</label>
<input id="password_confirm" name="password_confirm" type="password" required minlength="10" autocomplete="new-password"/>
<button type="submit">Save password</button>
<p class="links"><a href="login.php">Sign in</a> · <a href="register.php">Register</a></p>
</form>
</body>
</html>
