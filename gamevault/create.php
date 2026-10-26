<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_login();

$game = ['title' => '', 'platform' => '', 'genre' => '', 'status' => '', 'release_year' => ''];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valid()) {
        $errors['title'] = t('csrf_failed');
        $game = array_merge($game, array_intersect_key($_POST, $game));
    } else {
        [$game, $errors] = validate_game($_POST);
        if (!$errors) {
            $stmt = db()->prepare(
                'INSERT INTO games (title, platform, genre, status, release_year) VALUES (?, ?, ?, ?, ?)'
            );
            $stmt->execute([$game['title'], $game['platform'], $game['genre'], $game['status'], $game['release_year']]);
            flash('success', t('created'));
            redirect('index.php');
        }
    }
}

$pageTitle = t('add_title');
require __DIR__ . '/includes/header.php';
?>
<div class="card-wrapper">
    <div class="liquid-card-glow" aria-hidden="true"></div>
    <section class="card liquid-card">
        <div class="card-header">
            <div class="card-badge-capsule">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
            </div>
            <h1 class="card-title"><?= e(t('add_title')) ?></h1>
        </div>
        <?php $submitLabel = t('save_new'); require __DIR__ . '/includes/game_form.php'; ?>
    </section>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
