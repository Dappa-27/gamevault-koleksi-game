<?php
require_once __DIR__ . '/includes/bootstrap.php';

if (!empty($_SESSION['user_id'])) {
    redirect('index.php');
}

$error = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim(strip_tags((string) ($_POST['username'] ?? '')));
    $password = (string) ($_POST['password'] ?? '');

    if (!csrf_valid()) {
        $error = t('csrf_failed');
    } elseif ($username === '' || $password === '') {
        $error = t('login_empty');
    } else {
        $stmt = db()->prepare('SELECT id, username, password_hash FROM users WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);          // cegah session fixation
            $_SESSION['user_id']  = (int) $user['id'];
            $_SESSION['username'] = $user['username'];
            redirect('index.php');
        }
        $error = t('login_failed');
    }
}

$pageTitle = t('login_title');
require __DIR__ . '/includes/header.php';
?>
<div class="login-wrapper">
    <div class="liquid-card-glow" aria-hidden="true"></div>
    <section class="login liquid-card">
        <div class="login-header">
            <div class="login-badge-capsule">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
            </div>
            <h1 class="login-title"><?= e(t('login_title')) ?></h1>
            <p class="muted"><?= e(t('login_hint')) ?></p>
        </div>

        <?php if (!empty($flash)): ?>
            <div class="alert alert-<?= e($flash['type']) ?>" role="status">
                <span class="alert-icon">
                    <?php if ($flash['type'] === 'success'): ?>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"></path></svg>
                    <?php else: ?>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <?php endif; ?>
                </span>
                <span class="alert-msg"><?= e($flash['message']) ?></span>
            </div>
        <?php endif; ?>

        <?php if ($error !== ''): ?>
            <div class="alert alert-error" role="alert">
                <span class="alert-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                </span>
                <span class="alert-msg"><?= e($error) ?></span>
            </div>
        <?php endif; ?>

        <form method="post" class="form" novalidate>
            <?= csrf_field() ?>
            <div class="field">
                <label for="username"><?= e(t('username')) ?></label>
                <div class="input-with-icon">
                    <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <input type="text" id="username" name="username" value="<?= e($username) ?>" autocomplete="username" autofocus placeholder="admin">
                </div>
            </div>
            <div class="field">
                <label for="password"><?= e(t('password')) ?></label>
                <div class="input-with-icon">
                    <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <input type="password" id="password" name="password" autocomplete="current-password" placeholder="••••••••">
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-chrome btn-block">
                <span><?= e(t('login_button')) ?></span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </button>
        </form>
    </section>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
