<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 24-3-2023
 * Time: 22:46
 */
namespace TP_Content\Themes\custom_theme;
use TP_Core\Classes\CoreHelpers;
use TP_Core\Traits\components_methods;
if(ABSPATH) {
    class custom_index{
        use components_methods;
        public $passed_footer_args = [];
        public $passed_head_args = [];
        public function __construct() {
            $ch = new CoreHelpers();
            $ch->setCharsetHelper('UTF-8');
            $ch->setMetaHelper('text_html',null,'Content-Type','text/html');
            $ch->setMetaHelper('view_port','viewport',null,'width=device-width, initial-scale=1, maximum-scale=1.0');
            $ch->setTitleHelper('TP Project');
            $ch->setLogoHelper(CUSTOM_ICONS.'awd_logo.svg');
            $ch->setMetaHelper('text_html',null,'Content-Type','text/html');
            $ch->setMetaHelper('view_port','viewport',null,'width=device-width, initial-scale=1, maximum-scale=1.0');
            $ch->setTitleHelper('TP Project');
            $ch->setLogoHelper(CUSTOM_ICONS.'awd_logo.svg');
            $style_rule_1 = "body{background: #8cadd8; color:#fff;}";
            $style_rule_1 .= "footer{background: #d6b267; color:#fff;}";
            $style_rule_2 = "header{background: #d6b267; color:#fff;text-align: center;font-size:2.2rem;}";
            $ch->setCriticalStylesHelper($style_rule_1);
            $ch->setCriticalStylesHelper($style_rule_2);
            $ch->setCssHelper(CUSTOM_STYLES.'index.css');
            $ch->setCssHelper(CUSTOM_STYLES.'fake.css');
            $import_map = [[
                "import1" => "path/to/import1",
                "import2" => "path/to/import2",
                "import3" => "path/to/import3"
            ]];
            $ch->setImportmapHelper($import_map);
            $ch->setModuleJsHelper(CUSTOM_SCRIPTS . 'index.js');
            $ch->setJsHelper(CUSTOM_SCRIPTS . 'fake1.js');
            $ch->setJsHelper(CUSTOM_SCRIPTS . 'fake2.js', "type='text/javascript' ");
            /** @info The order of passed_head_args here matters,
             * it's to make sure that the tags appear on the right order into the head. */
            $this->passed_head_args[] = $ch->getCharsetHelper();
            $this->passed_head_args[] = $ch->getMetaHelper();
            $this->passed_head_args[] = $ch->getTitleHelper();
            $this->passed_head_args[] = $ch->getLogoHelper();
            $this->passed_head_args[] = $ch->getCriticalStylesHelper();
            $this->passed_head_args[] = $ch->getCssHelper();
            $this->passed_head_args[] = $ch->getImportmapHelper();
            $this->passed_head_args[] = $ch->getModuleJsHelper();
            $this->passed_head_args[] = $ch->getJsHelper();
            $ch->setConsoleHelper('test_id1','test_content1');
            $ch->setConsoleHelper('test_id2','test_content2');//CUSTOM_ICONS
            $this->passed_footer_args[] = $ch->getConsoleHelpers();
        }
        private function __to_string(){
            $html = "<!DOCTYPE html><html>";
            $html .= self::head_component(...$this->passed_head_args);
            $html .= "<body class='body_test'>";
            $html .= self::header_component();
            $html .= self::sidebar_component();
            $html .= self::overlay_component();
            $html .= self::footer_component(...$this->passed_footer_args);
            $html .= "</body>";
            $html .= "</html>";
            return $html;
        }
        public function __toString(){
            return $this->__to_string();
        }
    }
}else {die;}