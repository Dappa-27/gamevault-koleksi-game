<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_login();

// Filter & pencarian (GET, memakai prepared statement)
$q = trim(strip_tags((string) ($_GET['q'] ?? '')));
$statusFilter = (string) ($_GET['status'] ?? '');
if (!in_array($statusFilter, status_options(), true)) {
    $statusFilter = '';
}

$sql = 'SELECT * FROM games WHERE 1=1';
$params = [];
if ($q !== '') {
    $sql .= ' AND title LIKE ?';
    $params[] = '%' . $q . '%';
}
if ($statusFilter !== '') {
    $sql .= ' AND status = ?';
    $params[] = $statusFilter;
}
$sql .= ' ORDER BY title ASC';

$stmt = db()->prepare($sql);
$stmt->execute($params);
$games = $stmt->fetchAll();

// Jumlah per status
$counts = array_fill_keys(status_options(), 0);
foreach (db()->query('SELECT status, COUNT(*) AS n FROM games GROUP BY status') as $row) {
    $counts[$row['status']] = (int) $row['n'];
}
$total = array_sum($counts);

$pageTitle = t('collection_title');
require __DIR__ . '/includes/header.php';
?>
<div class="page-head">
    <div class="page-title-group">
        <h1 class="page-title"><?= e(t('collection_title')) ?></h1>
        <div class="stat-badge">
            <span class="stat-badge-dot"></span>
            <span class="stat-label"><?= e(t('total_games')) ?></span>
            <strong class="stat-num"><?= $total ?></strong>
        </div>
    </div>
    <a class="btn btn-primary btn-chrome" href="create.php">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        <span><?= e(t('nav_add')) ?></span>
    </a>
</div>

<div class="controls-bar">
    <div class="chips-scroll">
        <div class="chips">
            <a class="chip <?= $statusFilter === '' ? 'on' : '' ?>" href="index.php?q=<?= urlencode($q) ?>">
                <span class="chip-label"><?= e(t('filter_all')) ?></span>
                <span class="chip-count"><?= $total ?></span>
            </a>
            <?php foreach (status_options() as $s): ?>
                <a class="chip st-<?= e($s) ?> <?= $statusFilter === $s ? 'on' : '' ?>" href="index.php?status=<?= e($s) ?>&q=<?= urlencode($q) ?>">
                    <span class="status-indicator"></span>
                    <span class="chip-label"><?= e(t('status_' . $s)) ?></span>
                    <span class="chip-count"><?= $counts[$s] ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <form method="get" class="search">
        <?php if ($statusFilter !== ''): ?><input type="hidden" name="status" value="<?= e($statusFilter) ?>"><?php endif; ?>
        <div class="search-input-wrap">
            <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="search" name="q" value="<?= e($q) ?>" placeholder="<?= e(t('search_placeholder')) ?>" aria-label="<?= e(t('search_placeholder')) ?>">
            <?php if ($q !== ''): ?>
                <a href="index.php<?= $statusFilter !== '' ? '?status=' . urlencode($statusFilter) : '' ?>" class="search-clear" title="Clear">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </a>
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-ghost"><?= e(t('search_button')) ?></button>
    </form>
</div>

<?php if (!$games): ?>
    <div class="empty">
        <div class="empty-icon-wrap">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="6" width="20" height="12" rx="6"></rect>
                <path d="M6 12h4"></path>
                <path d="M8 10v4"></path>
                <circle cx="17" cy="10" r=".8" fill="currentColor"></circle>
                <circle cx="15" cy="13" r=".8" fill="currentColor"></circle>
            </svg>
        </div>
        <h2><?= e(t('empty_title')) ?></h2>
        <p><?= e(t('empty_text')) ?></p>
        <a class="btn btn-primary btn-chrome" href="create.php">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            <span><?= e(t('nav_add')) ?></span>
        </a>
    </div>
<?php else: ?>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th class="col-num"><?= e(t('col_no')) ?></th>
                    <th><?= e(t('col_title')) ?></th>
                    <th><?= e(t('col_platform')) ?></th>
                    <th><?= e(t('col_genre')) ?></th>
                    <th><?= e(t('col_status')) ?></th>
                    <th><?= e(t('col_year')) ?></th>
                    <th class="col-act"><?= e(t('col_actions')) ?></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($games as $i => $g): ?>
                <tr>
                    <td class="col-num"><span class="num-capsule"><?= $i + 1 ?></span></td>
                    <td class="title-cell">
                        <span class="game-title"><?= e($g['title']) ?></span>
                    </td>
                    <td><span class="plat-tag"><?= e(t('platform_' . $g['platform'])) ?></span></td>
                    <td><span class="genre-text"><?= e(t('genre_' . $g['genre'])) ?></span></td>
                    <td>
                        <span class="badge st-<?= e($g['status']) ?>">
                            <span class="badge-dot"></span>
                            <span class="badge-text"><?= e(t('status_' . $g['status'])) ?></span>
                        </span>
                    </td>
                    <td><span class="year-text"><?= (int) $g['release_year'] ?></span></td>
                    <td class="row-actions">
                        <a class="action-btn action-edit" href="edit.php?id=<?= (int) $g['id'] ?>" title="<?= e(t('edit')) ?>">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 20h9"></path>
                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                            </svg>
                            <span><?= e(t('edit')) ?></span>
                        </a>
                        <form method="post" action="delete.php" onsubmit="return confirm('<?= e(addslashes(t('confirm_delete'))) ?>');">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= (int) $g['id'] ?>">
                            <button type="submit" class="action-btn action-danger" title="<?= e(t('delete')) ?>">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                </svg>
                                <span><?= e(t('delete')) ?></span>
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
