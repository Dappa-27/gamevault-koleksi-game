<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_login();

$id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);
if ($id === false || $id < 1) {
    flash('error', t('not_found'));
    redirect('index.php');
}

$stmt = db()->prepare('SELECT * FROM games WHERE id = ?');
$stmt->execute([$id]);
$game = $stmt->fetch();
if (!$game) {
    flash('error', t('not_found'));
    redirect('index.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valid()) {
        $errors['title'] = t('csrf_failed');
    } else {
        [$clean, $errors] = validate_game($_POST);
        $game = array_merge($game, $clean);
        if (!$errors) {
            $upd = db()->prepare(
                'UPDATE games SET title = ?, platform = ?, genre = ?, status = ?, release_year = ? WHERE id = ?'
            );
            $upd->execute([$clean['title'], $clean['platform'], $clean['genre'], $clean['status'], $clean['release_year'], $id]);
            flash('success', t('updated'));
            redirect('index.php');
        }
    }
}

$pageTitle = t('edit_title');
require __DIR__ . '/includes/header.php';
?>
<div class="card-wrapper">
    <div class="liquid-card-glow" aria-hidden="true"></div>
    <section class="card liquid-card">
        <div class="card-header">
            <div class="card-badge-capsule">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 20h9"></path>
                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                </svg>
            </div>
            <h1 class="card-title"><?= e(t('edit_title')) ?></h1>
        </div>
        <?php $submitLabel = t('save_edit'); require __DIR__ . '/includes/game_form.php'; ?>
    </section>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
