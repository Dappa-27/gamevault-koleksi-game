<?php
// Partial form: butuh $game (array), $errors (array), $submitLabel (string)
?>
<form method="post" class="form" novalidate>
    <?= csrf_field() ?>

    <div class="field <?= isset($errors['title']) ? 'has-error' : '' ?>">
        <label for="title"><?= e(t('field_title')) ?></label>
        <div class="input-wrap">
            <input type="text" id="title" name="title" maxlength="150" value="<?= e($game['title']) ?>" placeholder="e.g. Elden Ring, The Witcher 3" required>
        </div>
        <?php if (isset($errors['title'])): ?>
            <p class="error">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <?= e($errors['title']) ?>
            </p>
        <?php endif; ?>
    </div>

    <div class="field-row">
        <div class="field <?= isset($errors['platform']) ? 'has-error' : '' ?>">
            <label for="platform"><?= e(t('field_platform')) ?></label>
            <div class="select-wrap">
                <select id="platform" name="platform" required>
                    <option value=""><?= e(t('choose')) ?></option>
                    <?php foreach (platform_options() as $p): ?>
                        <option value="<?= e($p) ?>" <?= $game['platform'] === $p ? 'selected' : '' ?>><?= e(t('platform_' . $p)) ?></option>
                    <?php endforeach; ?>
                </select>
                <svg class="select-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </div>
            <?php if (isset($errors['platform'])): ?>
                <p class="error">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <?= e($errors['platform']) ?>
                </p>
            <?php endif; ?>
        </div>

        <div class="field <?= isset($errors['genre']) ? 'has-error' : '' ?>">
            <label for="genre"><?= e(t('field_genre')) ?></label>
            <div class="select-wrap">
                <select id="genre" name="genre" required>
                    <option value=""><?= e(t('choose')) ?></option>
                    <?php foreach (genre_options() as $g): ?>
                        <option value="<?= e($g) ?>" <?= $game['genre'] === $g ? 'selected' : '' ?>><?= e(t('genre_' . $g)) ?></option>
                    <?php endforeach; ?>
                </select>
                <svg class="select-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </div>
            <?php if (isset($errors['genre'])): ?>
                <p class="error">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <?= e($errors['genre']) ?>
                </p>
            <?php endif; ?>
        </div>
    </div>

    <div class="field-row">
        <div class="field <?= isset($errors['status']) ? 'has-error' : '' ?>">
            <label for="status"><?= e(t('field_status')) ?></label>
            <div class="select-wrap">
                <select id="status" name="status" required>
                    <option value=""><?= e(t('choose')) ?></option>
                    <?php foreach (status_options() as $s): ?>
                        <option value="<?= e($s) ?>" <?= $game['status'] === $s ? 'selected' : '' ?>><?= e(t('status_' . $s)) ?></option>
                    <?php endforeach; ?>
                </select>
                <svg class="select-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </div>
            <?php if (isset($errors['status'])): ?>
                <p class="error">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <?= e($errors['status']) ?>
                </p>
            <?php endif; ?>
        </div>

        <div class="field <?= isset($errors['release_year']) ? 'has-error' : '' ?>">
            <label for="release_year"><?= e(t('field_year')) ?></label>
            <div class="input-wrap">
                <input type="number" id="release_year" name="release_year" value="<?= e((string) $game['release_year']) ?>" placeholder="e.g. 2024" required>
            </div>
            <?php if (isset($errors['release_year'])): ?>
                <p class="error">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <?= e($errors['release_year']) ?>
                </p>
            <?php endif; ?>
        </div>
    </div>

    <div class="actions">
        <button type="submit" class="btn btn-primary btn-chrome">
            <span><?= e($submitLabel) ?></span>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </button>
        <a class="btn btn-ghost" href="index.php"><?= e(t('cancel')) ?></a>
    </div>
</form>
