<label>
    <?= _('Theme:') ?>
    <select id="theme_chooser">
        <option value="" style="color: #888;"><?= _('Standard') ?></option>
    <? foreach ($editor_themes as $theme): ?>
        <option <? if ($theme === @$_COOKIE['customcss-theme']) echo 'selected'; ?>>
            <?= htmlReady($theme) ?>
        </option>
    <? endforeach; ?>
    </select>
</label>
