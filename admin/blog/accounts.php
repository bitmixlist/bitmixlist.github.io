<?php

declare(strict_types=1);

/**
 * Blog admin user accounts (file-backed).
 *
 * Roles: admin | editor
 * Status: pending | approved | active | disabled
 * - pending: registered, awaiting admin approval (no password)
 * - approved: approved by admin; user may set password once
 * - active: password set; may log in
 * - disabled: blocked from login
 */

function blog_accounts_path(): string
{
    $override = getenv('BITMIXLIST_BLOG_ACCOUNTS_PATH');
    if (is_string($override) && $override !== '') {
        return $override;
    }

    return __DIR__ . '/data/users.json';
}

/**
 * @return array{users: list<array<string, mixed>>, updated_at: string}
 */
function blog_accounts_load(): array
{
    $path = blog_accounts_path();
    if (!is_file($path)) {
        $data = blog_accounts_bootstrap();
        blog_accounts_save($data);

        return $data;
    }

    $raw = file_get_contents($path);
    $decoded = is_string($raw) ? json_decode($raw, true) : null;
    if (!is_array($decoded) || !isset($decoded['users']) || !is_array($decoded['users'])) {
        $data = blog_accounts_bootstrap();
        blog_accounts_save($data);

        return $data;
    }

    return [
        'users' => array_values($decoded['users']),
        'updated_at' => (string) ($decoded['updated_at'] ?? gmdate('c')),
    ];
}

/**
 * @param array{users: list<array<string, mixed>>, updated_at?: string} $data
 */
function blog_accounts_save(array $data): void
{
    $path = blog_accounts_path();
    $dir = dirname($path);
    if (!is_dir($dir) && !mkdir($dir, 0770, true) && !is_dir($dir)) {
        throw new RuntimeException('Unable to create accounts data directory');
    }

    $payload = [
        'updated_at' => gmdate('c'),
        'users' => array_values($data['users'] ?? []),
    ];
    $json = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    if ($json === false) {
        throw new RuntimeException('Unable to encode accounts data');
    }

    $tmp = $path . '.tmp.' . bin2hex(random_bytes(4));
    if (file_put_contents($tmp, $json . "\n", LOCK_EX) === false) {
        throw new RuntimeException('Unable to write accounts data');
    }
    if (!rename($tmp, $path)) {
        @unlink($tmp);
        throw new RuntimeException('Unable to finalize accounts data');
    }
    @chmod($path, 0660);
}

/**
 * Bootstrap: migrate legacy single-password admin into an admin account.
 *
 * @return array{users: list<array<string, mixed>>, updated_at: string}
 */
function blog_accounts_bootstrap(): array
{
    $hash = '';
    $hashFile = __DIR__ . '/admin-password.hash';
    if (is_file($hashFile)) {
        $hash = trim((string) file_get_contents($hashFile));
    }
    if ($hash === '') {
        $env = getenv('BITMIXLIST_BLOG_ADMIN_PASSWORD');
        if (is_string($env) && $env !== '') {
            $hash = password_hash($env, PASSWORD_DEFAULT);
        }
    }
    if ($hash === '') {
        // Local-only bootstrap when nothing is configured.
        $hash = password_hash('bitmixlist-local', PASSWORD_DEFAULT);
    }

    $now = gmdate('c');
    $admin = [
        'username' => 'notatether',
        'role' => 'admin',
        'status' => 'active',
        'password_hash' => $hash,
        'created_at' => $now,
        'approved_at' => $now,
        'approved_by' => 'system',
        'disabled_at' => null,
        'password_set_at' => $now,
    ];

    return [
        'users' => [$admin],
        'updated_at' => $now,
    ];
}

function blog_accounts_normalize_username(string $username): string
{
    $username = strtolower(trim($username));
    $username = preg_replace('/[^a-z0-9_\-\.]/', '', $username) ?? '';

    return $username;
}

function blog_accounts_username_valid(string $username): bool
{
    return (bool) preg_match('/^[a-z][a-z0-9_\-\.]{2,31}$/', $username);
}

/**
 * @return array<string, mixed>|null
 */
function blog_accounts_find(string $username): ?array
{
    $username = blog_accounts_normalize_username($username);
    if ($username === '') {
        return null;
    }
    $data = blog_accounts_load();
    foreach ($data['users'] as $user) {
        if (blog_accounts_normalize_username((string) ($user['username'] ?? '')) === $username) {
            return $user;
        }
    }

    return null;
}

/**
 * @param array<string, mixed> $user
 */
function blog_accounts_upsert(array $user): void
{
    $username = blog_accounts_normalize_username((string) ($user['username'] ?? ''));
    if ($username === '' || !blog_accounts_username_valid($username)) {
        throw new InvalidArgumentException('Invalid username');
    }
    $user['username'] = $username;
    $data = blog_accounts_load();
    $found = false;
    foreach ($data['users'] as $i => $existing) {
        if (blog_accounts_normalize_username((string) ($existing['username'] ?? '')) === $username) {
            $data['users'][$i] = $user;
            $found = true;
            break;
        }
    }
    if (!$found) {
        $data['users'][] = $user;
    }
    blog_accounts_save($data);
}

/**
 * Register a new editor (pending, no password).
 *
 * @return array{ok: bool, error?: string}
 */
function blog_accounts_register(string $username): array
{
    $username = blog_accounts_normalize_username($username);
    if (!blog_accounts_username_valid($username)) {
        return ['ok' => false, 'error' => 'Username must be 3–32 chars, start with a letter, and use only a–z, 0–9, _, -, .'];
    }
    if (in_array($username, ['admin', 'notatether'], true)) {
        return ['ok' => false, 'error' => 'That username is reserved.'];
    }
    if (blog_accounts_find($username) !== null) {
        return ['ok' => false, 'error' => 'That username is not available.'];
    }

    $now = gmdate('c');
    blog_accounts_upsert([
        'username' => $username,
        'role' => 'editor',
        'status' => 'pending',
        'password_hash' => null,
        'created_at' => $now,
        'approved_at' => null,
        'approved_by' => null,
        'disabled_at' => null,
        'password_set_at' => null,
    ]);

    return ['ok' => true];
}

/**
 * @return array{ok: bool, error?: string}
 */
function blog_accounts_approve(string $username, string $approverUsername): array
{
    $user = blog_accounts_find($username);
    if ($user === null) {
        return ['ok' => false, 'error' => 'User not found.'];
    }
    if (($user['role'] ?? '') === 'admin') {
        return ['ok' => false, 'error' => 'Cannot change admin via approval flow.'];
    }
    $status = (string) ($user['status'] ?? '');
    if (!in_array($status, ['pending', 'disabled', 'approved'], true)) {
        // active stays active unless disabled first
        if ($status === 'active') {
            return ['ok' => false, 'error' => 'User is already active.'];
        }
    }
    if ($status === 'disabled') {
        return ['ok' => false, 'error' => 'Re-enable the account first, or use reset password after enable.'];
    }

    $user['status'] = 'approved';
    $user['approved_at'] = gmdate('c');
    $user['approved_by'] = blog_accounts_normalize_username($approverUsername);
    $user['disabled_at'] = null;
    // Clear any previous hash so they must set password after approval.
    if ($status === 'pending' || empty($user['password_hash'])) {
        $user['password_hash'] = null;
        $user['password_set_at'] = null;
    }
    blog_accounts_upsert($user);

    return ['ok' => true];
}

/**
 * @return array{ok: bool, error?: string}
 */
function blog_accounts_disable(string $username, string $actorUsername): array
{
    $user = blog_accounts_find($username);
    if ($user === null) {
        return ['ok' => false, 'error' => 'User not found.'];
    }
    $target = blog_accounts_normalize_username((string) $user['username']);
    $actor = blog_accounts_normalize_username($actorUsername);
    if ($target === $actor) {
        return ['ok' => false, 'error' => 'You cannot disable your own account.'];
    }
    if ($target === 'notatether') {
        return ['ok' => false, 'error' => 'The primary admin account cannot be disabled.'];
    }

    $user['status'] = 'disabled';
    $user['disabled_at'] = gmdate('c');
    blog_accounts_upsert($user);

    return ['ok' => true];
}

/**
 * @return array{ok: bool, error?: string}
 */
function blog_accounts_enable(string $username): array
{
    $user = blog_accounts_find($username);
    if ($user === null) {
        return ['ok' => false, 'error' => 'User not found.'];
    }
    if (($user['status'] ?? '') !== 'disabled') {
        return ['ok' => false, 'error' => 'User is not disabled.'];
    }
    $hasPassword = is_string($user['password_hash'] ?? null) && (string) $user['password_hash'] !== '';
    $user['status'] = $hasPassword ? 'active' : 'approved';
    $user['disabled_at'] = null;
    blog_accounts_upsert($user);

    return ['ok' => true];
}

/**
 * Admin forces password re-set (clears hash, status approved).
 *
 * @return array{ok: bool, error?: string}
 */
function blog_accounts_reset_password(string $username): array
{
    $user = blog_accounts_find($username);
    if ($user === null) {
        return ['ok' => false, 'error' => 'User not found.'];
    }
    if (blog_accounts_normalize_username((string) $user['username']) === 'notatether') {
        return ['ok' => false, 'error' => 'Reset the primary admin password via server tools if needed.'];
    }
    if (($user['status'] ?? '') === 'pending') {
        return ['ok' => false, 'error' => 'Approve the registration first.'];
    }
    if (($user['status'] ?? '') === 'disabled') {
        return ['ok' => false, 'error' => 'Enable the account first.'];
    }

    $user['password_hash'] = null;
    $user['password_set_at'] = null;
    $user['status'] = 'approved';
    blog_accounts_upsert($user);

    return ['ok' => true];
}

/**
 * Set password only when status is approved (post-approval, pre-login).
 *
 * @return array{ok: bool, error?: string}
 */
function blog_accounts_set_password(string $username, string $password, string $passwordConfirm): array
{
    $username = blog_accounts_normalize_username($username);
    $user = blog_accounts_find($username);
    if ($user === null) {
        return ['ok' => false, 'error' => 'User not found.'];
    }
    if (($user['status'] ?? '') !== 'approved') {
        return ['ok' => false, 'error' => 'Password can only be set after an admin approves your registration.'];
    }
    if (is_string($user['password_hash'] ?? null) && (string) $user['password_hash'] !== '') {
        return ['ok' => false, 'error' => 'Password already set. Sign in instead.'];
    }
    if (strlen($password) < 10) {
        return ['ok' => false, 'error' => 'Password must be at least 10 characters.'];
    }
    if (!hash_equals($password, $passwordConfirm)) {
        return ['ok' => false, 'error' => 'Passwords do not match.'];
    }

    $user['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
    $user['password_set_at'] = gmdate('c');
    $user['status'] = 'active';
    blog_accounts_upsert($user);

    return ['ok' => true];
}

/**
 * @return array{ok: bool, user?: array<string, mixed>, error?: string}
 */
function blog_accounts_authenticate(string $username, string $password): array
{
    $user = blog_accounts_find($username);
    if ($user === null) {
        return ['ok' => false, 'error' => 'Invalid username or password.'];
    }
    $status = (string) ($user['status'] ?? '');
    if ($status === 'disabled') {
        return ['ok' => false, 'error' => 'This account is disabled.'];
    }
    if ($status === 'pending') {
        return ['ok' => false, 'error' => 'Registration is pending admin approval.'];
    }
    if ($status === 'approved') {
        return ['ok' => false, 'error' => 'Set your password first (after approval).'];
    }
    if ($status !== 'active') {
        return ['ok' => false, 'error' => 'Invalid username or password.'];
    }
    $hash = (string) ($user['password_hash'] ?? '');
    if ($hash === '' || !password_verify($password, $hash)) {
        return ['ok' => false, 'error' => 'Invalid username or password.'];
    }

    return ['ok' => true, 'user' => $user];
}

/**
 * @return list<array<string, mixed>>
 */
function blog_accounts_all(): array
{
    $data = blog_accounts_load();
    $users = $data['users'];
    usort($users, static function (array $a, array $b): int {
        return [(string) ($a['status'] ?? ''), (string) ($a['username'] ?? '')]
            <=> [(string) ($b['status'] ?? ''), (string) ($b['username'] ?? '')];
    });

    return $users;
}
