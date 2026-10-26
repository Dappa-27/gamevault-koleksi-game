<?php
require_once __DIR__ . '/includes/bootstrap.php';

// Simpan pilihan bahasa ke $_SESSION (hanya nilai yang diizinkan)
$lang = $_GET['lang'] ?? '';
if (in_array($lang, SUPPORTED_LANGS, true)) {
    $_SESSION['lang'] = $lang;
}

// Kembali ke halaman sebelumnya jika berasal dari host yang sama
$back = 'index.php';
if (!empty($_SERVER['HTTP_REFERER'])) {
    $ref = parse_url($_SERVER['HTTP_REFERER']);
    if (($ref['host'] ?? '') === ($_SERVER['HTTP_HOST'] ?? '') || ($ref['host'] ?? '') === explode(':', $_SERVER['HTTP_HOST'] ?? '')[0]) {
        $back = basename($ref['path'] ?? 'index.php');
        if ($back === '' || substr($back, -4) !== '.php') {
            $back = 'index.php';
        }
        if (!empty($ref['query']) && $back !== 'login.php') {
            $back .= '?' . $ref['query'];
        }
    }
}
redirect($back);
