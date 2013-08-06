<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<form action="?" method="post">
    <textarea name="custom_css" style="width: 100%; height: 400px; font-family: Courier New, MONOSPACE"><?= htmlReady($customcss['css']) ?></textarea>
    <br>
    <?= \Studip\Button::create(_("speichern")) ?>
</form>

<?
$infobox = array();

$infobox = array(
    'picture' => $GLOBALS['ABSOLUTE_URI_STUDIP'].$plugin->getPluginPath()."/assets/infobox.jpg",
    'content' => $infobox
);