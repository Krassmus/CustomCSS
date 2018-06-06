<?php

require_once dirname(__file__)."/models/CssModification.php";

class CustomCSS extends StudIPPlugin implements SystemPlugin {

    protected $cache;
    protected $cache_index;
    protected $editor_themes = array();

    public function __construct() {
        parent::__construct();

        if (Navigation::hasItem('/profile/settings')) {
            $navigation = new Navigation(_('Mein CSS'), PluginEngine::getURL($this, array(), 'css'));
            Navigation::addItem('/profile/settings/customcss', $navigation);

            $navigation = new Navigation(_('Mein HTML'), PluginEngine::getURL($this, array(), 'html'));
            Navigation::addItem('/profile/settings/customcss_html', $navigation);
        }

        $this->cache       = StudipCacheFactory::getCache();
        $this->cache_index = sprintf('custom-css-%s', $GLOBALS['user']->id);

        $css = $this->cache->read($this->cache_index);
        if ($css === false) {
            $stylesheet = CssModification::findMine();
            if ($stylesheet['css']) {
                $css  = $stylesheet['css'];

                $less = '';
                $mixinFile = $GLOBALS['STUDIP_BASE_PATH'] . '/resources/assets/stylesheets/mixins.less';
                foreach (file($mixinFile) as $mixin) {
                    if (!preg_match('/@import "(.*)";/', $mixin, $match)) {
                        continue;
                    }
                    $less .= file_get_contents($GLOBALS['STUDIP_BASE_PATH'] . '/resources/assets/stylesheets/' . $match[1]) . "\n";
                }
                $less .= sprintf('@image-path: "%s";', Assets::url('images')) . "\n";
                $less .= '@icon-path: "@{image-path}/icons/16";' . "\n";
                $less .= $css;

                try {
                    if (class_exists('Assets\\Compiler')) {
                        $css = Assets\Compiler::compileLESS($less);
                    } else {
                        require_once 'vendor/mishal-iless/lib/ILess/Autoloader.php';
                        ILess_Autoloader::register();
                        $parser = new ILess_Parser();
                        $parser->setVariables(array(
                            'image-path' => '"' . substr(Assets::image_path('placeholder.png'), 0, -15) . '"',
                        ));
                        $parser->parseString($less);
                        $css = $parser->getCSS();
                    }
                    $this->cache->write($this->cache_index, $css);
                } catch(Exception $e) {
                    PageLayout::clearMessages();
                    PageLayout::postMessage(MessageBox::error(_("Ihr Stylesheet enthält Syntaxfehler.")));
                }
            }
        }
        if ($css) {
            PageLayout::addBodyElements('<style>' . $css . '</style>');
        }
    }

    public function initialize()
    {
        // Include CodeMirror syntax highlighted editor <http://codemirror.net>
        PageLayout::addStylesheet($this->getPluginURL(). '/assets/codemirror/codemirror.css');
        PageLayout::addScript($this->getPluginURL(). '/assets/codemirror/codemirror.js');
        PageLayout::addScript($this->getPluginURL(). '/assets/codemirror/active-line.js');
        PageLayout::addScript($this->getPluginURL(). '/assets/codemirror/match-brackets.js');
        PageLayout::addScript($this->getPluginURL(). '/assets/codemirror/css.js');
        PageLayout::addScript($this->getPluginURL(). '/assets/codemirror/less.js');
        PageLayout::addScript($this->getPluginURL(). '/assets/codemirror/xml.js');
        PageLayout::addScript($this->getPluginURL(). '/assets/codemirror/htmlmixed.js');

        foreach (glob($this->getPluginPath() . '/assets/codemirror/theme/*.css') as $theme) {
            $theme = str_replace($this->getPluginPath(), '', $theme);
            PageLayout::addStylesheet($this->getPluginURL() . $theme);

            $this->editor_themes[] = basename($theme, '.css');
        }

        PageLayout::addScript($this->getPluginURL(). '/assets/customcss.js');
        $this->addStylesheet('assets/customcss.less');
    }


    protected function getDisplayName() {
        return _("Mein CSS");
    }

    public function css_action() {
        Navigation::activateItem('/profile/settings/customcss');
        $stylesheet = CssModification::findMine();
        if (Request::isPost() && Request::submitted("custom_css")) {
            $stylesheet['css'] = Request::get("custom_css");
            $stylesheet->store();

            $this->cache->expire($this->cache_index);

            PageLayout::postMessage(MessageBox::info(_('Ihr CSS wurde gespeichert.')));

            header('Location: '.PluginEngine::getURL($this, array(), 'css'));
            die;
        }

        $template = $this->getTemplate("css.php");
        $template->set_attribute("plugin", $this);
        $template->set_attribute("customcss", $stylesheet);
        $template->set_attribute('editor_themes', $this->editor_themes);
        echo $template->render();
    }

    public function html_action()
    {
        Navigation::activateItem('/profile/settings/customcss_html');

        $stylesheet = CssModification::findMine();

        if (Request::isPost()) {
            $stylesheet['html'] = Request::get('html');
            $stylesheet['css'] = $stylesheet['css'] ?: '';
            $stylesheet->store();

            PageLayout::postMessage(MessageBox::info(_('Ihr HTML wurde gespeichert.')));

            header('Location: ' . PluginEngine::getURL($this, array(), 'html'));
            die;
        }

        $template = $this->getTemplate('html.php');
        $template->html          = $stylesheet['html'];
        $template->plugin        = $this;
        $template->editor_themes = $this->editor_themes;
        $template->mode          = Request::option('mode', 'display');
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
            $template->set_layout($GLOBALS['template_factory']->open('layouts/base'));
        }
        return $template;
    }

}
