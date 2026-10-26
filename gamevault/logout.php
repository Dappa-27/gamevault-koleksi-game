<?php
require_once __DIR__ . '/includes/bootstrap.php';

$lang = $_SESSION['lang'] ?? 'id'; // pertahankan pilihan bahasa
$_SESSION = [];
if (session_status() === PHP_SESSION_ACTIVE) {
    session_regenerate_id(true);
}

$_SESSION['lang'] = $lang;
flash('success', t('logged_out'));
redirect('login.php');

