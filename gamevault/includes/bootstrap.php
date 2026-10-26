<?php
// Dimuat di awal setiap halaman: session, bahasa, helper.
session_start();

require_once __DIR__ . '/../config/database.php';

// ---------- Bahasa (disimpan di $_SESSION) ----------
const SUPPORTED_LANGS = ['id', 'en'];
if (empty($_SESSION['lang']) || !in_array($_SESSION['lang'], SUPPORTED_LANGS, true)) {
    $_SESSION['lang'] = 'id'; // default Bahasa Indonesia
}
$GLOBALS['LANG'] = require __DIR__ . '/../lang/' . $_SESSION['lang'] . '.php';

/** Ambil teks terjemahan berdasarkan kunci. */
function t(string $key): string
{
    return $GLOBALS['LANG'][$key] ?? $key;
}

// ---------- Helper ----------
/** Escape output HTML (anti-XSS). */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function pull_flash(): ?array
{
    $f = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $f;
}

// ---------- Proteksi halaman ----------
function require_login(): void
{
    if (empty($_SESSION['user_id'])) {
        redirect('login.php');
    }
}

// ---------- CSRF ----------
function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function csrf_valid(): bool
{
    return isset($_POST['csrf'], $_SESSION['csrf'])
        && hash_equals($_SESSION['csrf'], (string) $_POST['csrf']);
}

// ---------- Master data pilihan ----------
function platform_options(): array
{
    return ['pc', 'playstation', 'xbox', 'switch', 'mobile', 'other'];
}

function genre_options(): array
{
    return ['action', 'adventure', 'rpg', 'strategy', 'sports', 'racing', 'simulation', 'puzzle', 'horror', 'other'];
}

function status_options(): array
{
    return ['playing', 'completed', 'backlog', 'wishlist'];
}

// ---------- Validasi & sanitasi data game ----------
/**
 * Mengembalikan [data_bersih, daftar_error].
 * Menggunakan if-else, switch, filter_var, dan trim/strip_tags.
 */
function validate_game(array $input): array
{
    $errors = [];

    // Sanitasi: buang tag HTML dan spasi berlebih
    $title    = trim(strip_tags((string) ($input['title'] ?? '')));
    $platform = trim((string) ($input['platform'] ?? ''));
    $genre    = trim((string) ($input['genre'] ?? ''));
    $status   = trim((string) ($input['status'] ?? ''));
    $yearRaw  = trim((string) ($input['release_year'] ?? ''));

    // Judul
    if ($title === '') {
        $errors['title'] = t('err_title_required');
    } elseif (mb_strlen($title) > 150) {
        $errors['title'] = t('err_title_long');
    }

    // Platform
    if ($platform === '') {
        $errors['platform'] = t('err_platform_required');
    } elseif (!in_array($platform, platform_options(), true)) {
        $errors['platform'] = t('err_invalid_choice');
    }

    // Genre
    if ($genre === '') {
        $errors['genre'] = t('err_genre_required');
    } elseif (!in_array($genre, genre_options(), true)) {
        $errors['genre'] = t('err_invalid_choice');
    }

    // Status bermain (switch)
    switch ($status) {
        case 'playing':
        case 'completed':
        case 'backlog':
        case 'wishlist':
            break;
        case '':
            $errors['status'] = t('err_status_required');
            break;
        default:
            $errors['status'] = t('err_invalid_choice');
    }

    // Tahun rilis (filter_var)
    $maxYear = (int) date('Y') + 3;
    $year = filter_var($yearRaw, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1970, 'max_range' => $maxYear],
    ]);
    if ($yearRaw === '') {
        $errors['release_year'] = t('err_year_required');
    } elseif ($year === false) {
        $errors['release_year'] = sprintf(t('err_year_range'), 1970, $maxYear);
    }

    return [[
        'title'        => $title,
        'platform'     => $platform,
        'genre'        => $genre,
        'status'       => $status,
        'release_year' => $year === false ? null : $year,
    ], $errors];
}
