<?php

require_once dirname(__file__)."/models/CssModification.php";

class CustomCSS extends StudIPPlugin implements SystemPlugin {
    
    public function __construct() {
        parent::__construct();
        $navigation = new Navigation(_("Mein CSS"), PluginEngine::getURL($this, array(), 'css'));
        Navigation::addItem("/links/settings/customcss", $navigation);
        $stylesheet = CssModification::findMine();
        if ($stylesheet['css']) {
            PageLayout::addBodyElements('<style>'.($stylesheet['css']).'</style>');
        }
    }

    protected function getDisplayName() {
        return _("Mein CSS");
    }
    
    public function css_action() {
        Navigation::activateItem('/links/settings/customcss');
        $stylesheet = CssModification::findMine();
        if (Request::isPost() && Request::submitted("custom_css")) {
            $stylesheet['css'] = Request::get("custom_css");
            $stylesheet->store();
            header("Location: ".URLHelper::getURL("plugins.php/customcss/css", array(), null));
        }
        
        $template = $this->getTemplate("css.php");
        $template->set_attribute("plugin", $this);
        $template->set_attribute("customcss", $stylesheet);
        echo $template->render();
    }

    public function share_action() {
        $stylesheet = CssModification::findMine();
        $output = array();
        if (class_exists("BlubberPosting") && $GLOBALS['user']->id !== "nobody" && Request::isPost()) {
            $posting = new BlubberPosting();
            $posting['Seminar_id'] = $GLOBALS['user']->id;
            $posting['user_id'] = $GLOBALS['user']->id;
            $posting['description'] = "#MeinCSS für Stud.IP\n\n[code]\n".$stylesheet['css']."\n[/code]\n\n--Zum Ausprobieren, kopiere das CSS und füge es in [MeinCSS]".$GLOBALS['ABSOLUTE_URI_STUDIP']."plugins.php/customcss/css ein.--";
            $posting['name'] = "#MeinCSS";
            $posting['context_type'] = "public";
            $posting['external_contact'] = "0";
            $posting['parent_id'] = "0";
            $posting->store();
        }
        echo json_encode(studip_utf8encode($output));
    }
    
    protected function getTemplate($template_file_name, $layout = "without_infobox") {
        if (!$this->template_factory) {
            $this->template_factory = new Flexi_TemplateFactory(dirname(__file__)."/templates");
        }
        $template = $this->template_factory->open($template_file_name);
        if ($layout) {
            if (method_exists($this, "getDisplayName")) {
                PageLayout::setTitle($this->getDisplayName());
            } else {
                PageLayout::setTitle(get_class($this));
            }
            $template->set_layout($GLOBALS['template_factory']->open($layout === "without_infobox" ? 'layouts/base_without_infobox' : 'layouts/base'));
        }
        return $template;
    }
    
}