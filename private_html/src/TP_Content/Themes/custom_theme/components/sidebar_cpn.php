<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 27-3-2023
 * Time: 22:04
 */
namespace TP_Content\Themes\custom_theme\components;
if(ABSPATH) {
    class sidebar_cpn{
		private $__sb_args;
        public function __construct($args= null){
            $this->__sb_args = $args;
        }
        private function __to_string(){
            $html = "";
            $html .= "";
            $html .= "sidebar_cpn";
            $html .= "";
            $html .= "";
            return $html;
        }
        public function __toString(){
            return $this->__to_string();
        }
    }
}else {die;}