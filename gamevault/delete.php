<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_login();

// Hapus hanya lewat POST + token CSRF
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_valid()) {
    flash('error', t('csrf_failed'));
    redirect('index.php');
}

$id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
if ($id === false || $id < 1) {
    flash('error', t('not_found'));
    redirect('index.php');
}

$stmt = db()->prepare('DELETE FROM games WHERE id = ?');
$stmt->execute([$id]);

if ($stmt->rowCount() > 0) {
    flash('success', t('deleted'));
} else {
    flash('error', t('not_found'));
}
redirect('index.php');
