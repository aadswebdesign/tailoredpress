<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 24-3-2023
 * Time: 22:46
 */
namespace TP_Admin;
use TP_Core\Classes\CoreHelpers;
use TP_Admin\components\header_cpn;
use TP_Admin\components\overlay_cpn;
use TP_Admin\components\sidebar_cpn;
use TP_Admin\classes\admins;
use TP_Core\Libs\Components\FooterComponent;
use TP_Core\Libs\Components\HeadComponent;
if(ABSPATH) {
    class adminIndex extends admins {
        private $__footer_args; //content for between the footer tags
        private $__head_args; //content for between the head tags
        private $__logs; //logs
        public function __construct() {
            parent::__construct();
            $ch = new CoreHelpers();
            $ch->setCharsetHelper('UTF-8');
            $ch->setMetaHelper('text_html',null,'Content-Type','text/html');
            $ch->setMetaHelper('view_port','viewport',null,'width=device-width, initial-scale=1, maximum-scale=1.0');
            $ch->setTitleHelper('TP Project Developer Admin');
            $ch->setLogoHelper(ADM_ICONS.'awd_logo.svg');


            $this->__head_args  = $ch->getCharsetHelper();
            $this->__head_args .= $ch->getMetaHelper();
            $this->__head_args .= $ch->getTitleHelper();
            $this->__head_args .= $ch->getLogoHelper();
            $ch->setConsoleHelper('adm_test_id1','test_content1');
            $ch->setConsoleHelper('adm_test_id2','test_content2');
            $ch->setConsoleHelper('adm_test_id3','test_content3');
            $this->__logs = $ch->getConsoleHelpers();
        }
        private function __to_string(){
            $html = "<!DOCTYPE html><html>";
            $html .= new HeadComponent($this->__head_args);
            $html .= "<body>";
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