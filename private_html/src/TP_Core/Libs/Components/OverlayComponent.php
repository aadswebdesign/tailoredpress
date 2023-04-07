<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 28-3-2023
 * Time: 12:06
 */
namespace TP_Core\Libs\Components;
if(ABSPATH) {
    class OverlayComponent{
		private $__ol_args;
        public function __construct($args= null){
            $this->__ol_args = $args;
        }
        private function __to_string(){
            $html = "<div class='overlay display-flex static'>";
            $html .= "component ";
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