<?php

declare(strict_types=1);

require_once __DIR__ . '/auth.php';

blog_admin_session_start();
$error = '';

if (blog_admin_current_user() !== null) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = (string) ($_POST['username'] ?? '');
    $password = (string) ($_POST['password'] ?? '');
    $result = blog_accounts_authenticate($username, $password);
    if ($result['ok'] && isset($result['user']) && is_array($result['user'])) {
        blog_admin_login_user($result['user']);
        header('Location: index.php');
        exit;
    }
    $error = (string) ($result['error'] ?? 'Invalid username or password.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title>Blog Login – BitMixList</title>
<style>
body { font-family: system-ui, sans-serif; background: #121018; color: #f2ecff; margin: 0; min-height: 100vh; display: grid; place-items: center; }
form { width: min(360px, 92vw); padding: 24px; border: 1px solid #3a2e55; border-radius: 12px; background: #1a1230; }
h1 { margin: 0 0 12px; font-size: 1.25rem; }
label { display: block; margin: 12px 0 6px; font-size: 0.9rem; color: #c9c3d8; }
input { width: 100%; box-sizing: border-box; padding: 10px 12px; border-radius: 8px; border: 1px solid #4a3a70; background: #0f0d16; color: #fff; }
button { margin-top: 16px; width: 100%; padding: 10px; border: 0; border-radius: 8px; background: #7a61f6; color: #fff; font-weight: 650; cursor: pointer; }
.error { color: #ffb4b4; margin: 0 0 8px; font-size: 0.9rem; }
.links { margin-top: 16px; font-size: 0.9rem; color: #9b93ad; }
.links a { color: #c7b8ff; }
</style>
</head>
<body>
<form method="post" action="login.php" autocomplete="username">
<h1>BitMixList Blog</h1>
<?php if ($error !== ''): ?><p class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
<label for="username">Username</label>
<input id="username" name="username" type="text" required autofocus autocomplete="username"/>
<label for="password">Password</label>
<input id="password" name="password" type="password" required autocomplete="current-password"/>
<button type="submit">Sign in</button>
<p class="links"><a href="register.php">Request editor access</a> · <a href="set-password.php">Set password</a></p>
</form>
</body>
</html>
