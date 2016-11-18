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
$actions = new ActionsWidget();
$actions->addLink(
    _("HTML bearebeiten."),
    PluginEngine::getURL($plugin, array('mode' => 'edit'), 'html'),
    Icon::create('edit'),
    [],
    'edit_html'
);
Sidebar::Get()->addWidget($actions);

$widget = new SidebarWidget();
$widget->setTitle(_('Einstellungen'));
$widget->addElement(new WidgetElement($this->render_partial('settings')));
Sidebar::Get()->addWidget($widget);