<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 24-3-2023
 * Time: 22:46
 */
namespace TP_Admin;
//use TP_Core\Classes\CoreHelpers;
use TP_Admin\traits\adm_components_methods;
use TP_Admin\classes\admins;
if(ABSPATH) {
    class adminIndex extends admins {
        use adm_components_methods;
        private $__head_args =[];
        public function __construct() {
            parent::__construct();
            //$ch = new CoreHelpers();
            $this->__head_args[] = '';
        }
        private function __to_string(){
            $html = "<!DOCTYPE html><html>";
            $html .= self::head_component(...$this->__head_args);
            $html .= "<body>";
            $html .= self::header_component();
            $html .= self::sidebar_component();
            $html .= self::footer_component();
            $html .= self::overlay_component();
            $html .= "</body>";
            $html .= "</html>";

            return $html;
        }
        public function __toString(){
            return $this->__to_string();
        }

    }
}else {die;}