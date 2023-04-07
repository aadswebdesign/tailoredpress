<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 28-3-2023
 * Time: 12:06
 */
namespace TP_Admin\components;
use TP_Core\Libs\Components\OverlayComponent;
if(ABSPATH) {
    class overlay_cpn extends OverlayComponent{
		private $__ol_args;
        public function __construct($args= null){
            parent::__construct();
            $this->__ol_args = $args;
        }
        private function __to_string(){
            $html = "<div class='overlay display-flex static'>";
            $html .= "admin component ";
            $html .= "for";
            $html .= " pop up like";
            $html .= " content";
            $html .= "</div>";
            return $html;
        }
        public function __toString(){
            return $this->__to_string();
        }
    }
}else {die;}