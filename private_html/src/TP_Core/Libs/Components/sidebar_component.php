<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 27-3-2023
 * Time: 22:04
 */
namespace TP_Core\Libs\Components;
if(ABSPATH) {
    class sidebar_component{
		private sb_args;
        public function __construct($args= null){
            $this->sb_args = $args;
        }
        private function __to_string(){
            $html = "sidebar_component";
            $html .= "";
            return $html;
        }
        public function __toString(){
            return $this->__to_string();
        }
    }
}else {die;}