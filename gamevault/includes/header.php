<?php
// Variabel opsional: $pageTitle
$flash = pull_flash();
$current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="<?= e($_SESSION['lang']) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle ?? 'GameVault') ?> · GameVault</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css?v=<?= file_exists(__DIR__ . '/../assets/style.css') ? filemtime(__DIR__ . '/../assets/style.css') : time() ?>">
</head>
<body>
<!-- Ambient liquid light caustics for refractive glass depth -->
<div class="liquid-bg-effects" aria-hidden="true">
    <div class="liquid-orb orb-1"></div>
    <div class="liquid-orb orb-2"></div>
    <div class="liquid-orb orb-3"></div>
    <div class="liquid-mesh-grid"></div>
</div>

<header class="topbar-wrapper">
    <div class="topbar">
        <a class="brand" href="index.php">
            <span class="brand-chrome-capsule">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="6" width="20" height="12" rx="6"></rect>
                    <path d="M6 12h4"></path>
                    <path d="M8 10v4"></path>
                    <circle cx="17" cy="10" r=".8" fill="currentColor"></circle>
                    <circle cx="15" cy="13" r=".8" fill="currentColor"></circle>
                </svg>
            </span>
            <span class="brand-title">Game<span class="brand-highlight">Vault</span></span>
        </a>

        <nav class="nav">
            <?php if (!empty($_SESSION['user_id'])): ?>
                <a href="index.php" class="nav-link <?= $current === 'index.php' ? 'active' : '' ?>">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <span><?= e(t('nav_collection')) ?></span>
                </a>
                <a href="create.php" class="nav-link <?= $current === 'create.php' ? 'active' : '' ?>">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span><?= e(t('nav_add')) ?></span>
                </a>
            <?php endif; ?>
        </nav>

        <div class="topbar-right">
            <div class="lang-switch" aria-label="<?= e(t('language')) ?>">
                <a href="set_lang.php?lang=id" class="lang-pill <?= $_SESSION['lang'] === 'id' ? 'on' : '' ?>">ID</a>
                <a href="set_lang.php?lang=en" class="lang-pill <?= $_SESSION['lang'] === 'en' ? 'on' : '' ?>">EN</a>
            </div>

            <?php if (!empty($_SESSION['user_id'])): ?>
                <div class="user-chip">
                    <span class="user-pulse"></span>
                    <span class="who"><?= e($_SESSION['username']) ?></span>
                </div>
                <a class="btn btn-ghost btn-sm" href="logout.php">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    <span><?= e(t('logout')) ?></span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>

<main class="container">
<?php if ($flash && $current !== 'login.php'): ?>
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
