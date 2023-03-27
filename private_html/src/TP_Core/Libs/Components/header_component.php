<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 27-3-2023
 * Time: 22:04
 */
namespace TP_Core\Libs\Components;
if(ABSPATH) {
    class header_component{
 		private $hd_args;
        public function __construct($args= null){
            $this->hd_args = $args;
        }
        private function __to_string(){
            $html = "header_component";
            $html .= "";
            return $html;
        }
        public function __toString(){
            return $this->__to_string();
        }
    }
}else {die;}