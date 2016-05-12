<? if ($mode !== 'edit'): ?>
<div class="customcss-html">
    <?= $html ?>
</div>
<? else: ?>
<div class="customcss-html-edit">
    <form action="<?= PluginEngine::getURL($plugin, array(), 'html') ?>" method="post">
        <textarea name="html" id="customcss-editor" data-mode="htmlmixed"><?= htmlReady($html) ?></textarea>
        <br>
        <?= Studip\Button::createAccept(_('Speichern')) ?>
        <?= Studip\LinkButton::createCancel(_('Abbrechen'), PluginEngine::getURL($plugin, array(), 'html')) ?>
    </form>
</div>
<? endif; ?>

<?
$infobox = array(
    array(
        'kategorie' => _('Aktionen'),
        'eintrag' => array(
            array('icon' => "icons/16/black/edit", 'text' => sprintf(_('%sHTML bearbeiten%s'), '<a href="'.PluginEngine::getURL($plugin, array('mode' => 'edit'), 'html') . '">', '</a>'))
        )
    ),
    array(
        'kategorie' => _('Einstellungen'),
        'eintrag' => array(
          array('icon' => 'icons/16/black/admin', 'text' => $this->render_partial('settings')),   
        ),
    ),
);

$infobox = array(
    'picture' => $GLOBALS['ABSOLUTE_URI_STUDIP'].$plugin->getPluginPath()."/assets/infobox.jpg",
    'content' => $infobox
);