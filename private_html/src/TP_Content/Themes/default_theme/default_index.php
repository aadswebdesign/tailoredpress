<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 24-3-2023
 * Time: 22:46
 */
namespace TP_Content\Themes\default_theme;
if(ABSPATH) {
    class default_index{
        public function __construct() {
        }
        private function __to_string(){
            $html = "default_index with auto loader.";
            $html .= "";
            return $html;
        }
        public function __toString(){
            return $this->__to_string();
        }
    }
}else {die;}