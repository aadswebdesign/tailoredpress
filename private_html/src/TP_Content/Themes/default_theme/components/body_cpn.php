<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 27-3-2023
 * Time: 22:04
 */
namespace TP_Content\Themes\default_theme\components;
use TP_Core\Libs\Components\BodyComponent;
if(ABSPATH) {
    class body_cpn extends BodyComponent{
		private $__bd_args;
        public function __construct($args= null){
            parent::__construct();
            $this->__bd_args = $args;
        }
        private function __to_string(){
            $html = "";
            $html .= "";
            return $html;
        }
        public function __toString(){
            return $this->__to_string();
        }
    }
}else {die;}