<form action="?" method="post">
    <textarea name="custom_css" id="customcss-editor" data-mode="less"><?= htmlReady($customcss['css']) ?></textarea>
    <br>
    <?= \Studip\Button::create(_("speichern")) ?>
</form>

<?
$actions = new ActionsWidget();
$actions->addLink(
    _("Mein CSS über Blubber teilen."),
    URLHelper::getURL("plugins.php/blubber/streams/global?hash=MeinCSS"),
    Icon::create('community'),
    [],
    'share_via_blubber'
);
Sidebar::Get()->addWidget($actions);


$widget = new SidebarWidget();
$widget->setTitle(_('Einstellungen'));
$widget->addElement(new WidgetElement($this->render_partial('settings')));
Sidebar::Get()->addWidget($widget);
