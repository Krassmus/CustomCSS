<form action="?" method="post">
    <textarea name="custom_css" id="customcss-editor" data-mode="less"><?= htmlReady($customcss['css']) ?></textarea>
    <br>
    <?= \Studip\Button::create(_("speichern")) ?>
</form>

<?
$infobox = array(
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

$actions = new ActionsWidget();
$actions->addLink(
    _("Mein CSS über Blubber teilen."),
    URLHelper::getURL("plugins.php/blubber/streams/global?hash=MeinCSS"),
    'icons/16/black/community',
    array(),
    'share_via_blubber'
);
Sidebar::Get()->addWidget($actions);