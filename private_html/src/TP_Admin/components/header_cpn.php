<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 27-3-2023
 * Time: 22:04
 */
namespace TP_Admin\components;
if(ABSPATH) {
    class header_cpn{
 		private $hdr_args;
        public function __construct($args= null){
            $this->hdr_args = $args;
        }
        private function __to_string(){
            $hdr = "<section class='header display-flex relative'>";//or static
            $hdr .= "<header class='relative'>";
            $hdr .= "admin header element";
            $hdr .= "</header>";
            $hdr .= "navigation area";
			$hdr .= "</section>";
            return $hdr;
        }
        public function __toString(){
            return $this->__to_string();
        }
    }
}else {die;}