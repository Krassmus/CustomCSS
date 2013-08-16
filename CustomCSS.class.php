<?php

require_once dirname(__file__)."/models/CssModification.php";

class CustomCSS extends StudIPPlugin implements SystemPlugin {
    
    public function __construct() {
        parent::__construct();
        $navigation = new Navigation(_("CSS"), PluginEngine::getURL($this, array(), 'css'));
        Navigation::addItem("/links/settings/customcss", $navigation);
        $stylesheet = CssModification::findMine();
        if ($stylesheet['css']) {
            PageLayout::addBodyElements('<style>'.($stylesheet['css']).'</style>');
        }
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