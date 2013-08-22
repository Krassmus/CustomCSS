<form action="?" method="post">
    <textarea name="custom_css" id="customcss-editor" data-mode="less"><?= htmlReady($customcss['css']) ?></textarea>
    <br>
    <?= \Studip\Button::create(_("speichern")) ?>
</form>

<?
$infobox = array(
    array(
        'kategorie' => _("Information"),
        'eintrag' => array(
            array('icon' => "icons/16/black/code", 'text' => _("Geben Sie eigenes CSS ein, das Stud.IP exklusiv nur für Sie anders aussehen lässt.")),
            array('icon' => "icons/16/black/community", 'text' => sprintf(_("Teilen Sie Ihr CSS mit anderen über %sBlubber%s."), '<a href="'.URLHelper::getLink("plugins.php/blubber/streams/global?hash=MeinCSS").'" id="share_via_blubber">', '</a>'))
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
