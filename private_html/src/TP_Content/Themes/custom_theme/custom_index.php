<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 24-3-2023
 * Time: 22:46
 */
namespace TP_Content\Themes\custom_theme;
use TP_Core\Classes\CoreHelpers;
use TP_Core\Libs\Components\FooterComponent;
use TP_Core\Libs\Components\HeadComponent;
use TP_Content\Themes\custom_theme\components\header_cpn;
use TP_Content\Themes\custom_theme\components\overlay_cpn;
use TP_Content\Themes\custom_theme\components\sidebar_cpn;
if(ABSPATH) {
    class custom_index{
        private $__footer_args; //content for between the footer tags
        private $__head_args; //content for between the head tags
        private $__logs; //logs
        public function __construct() {
            $ch = new CoreHelpers();
            $ch->setCharsetHelper('UTF-8');
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
            /** @info The order of __head_args here matters,
             * it's to make sure that the tags appear on the right order into the head. */
            $this->__head_args  = $ch->getCharsetHelper();
            $this->__head_args .= $ch->getMetaHelper();
            $this->__head_args .= $ch->getTitleHelper();
            $this->__head_args .= $ch->getLogoHelper();
            $this->__head_args .= $ch->getCriticalStylesHelper();
            $this->__head_args .= $ch->getCssHelper();
            $this->__head_args .= $ch->getImportmapHelper();
            $this->__head_args .= $ch->getModuleJsHelper();
            $this->__head_args .= $ch->getJsHelper();
            $ch->setConsoleHelper('test_id1','test_content1');
            $ch->setConsoleHelper('test_id2','test_content2');
            $ch->setConsoleHelper('test_id3','test_content3');
            $this->__logs = $ch->getConsoleHelpers();
        }
        private function __to_string(){
            $html = "<!DOCTYPE html><html>";
            $html .= new HeadComponent($this->__head_args);
            $html .= "<body class='body_test'>";
            $html .= new header_cpn();
            $html .= new sidebar_cpn();
            $html .= new overlay_cpn();
            $html .= new FooterComponent($this->__footer_args,$this->__logs);
            $html .= "</body>";
            $html .= "</html>";
            return $html;
        }
        public function __toString(){
            return $this->__to_string();
        }
    }
}else {die;}