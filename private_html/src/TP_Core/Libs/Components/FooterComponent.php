<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 27-3-2023
 * Time: 22:04
 */
namespace TP_Core\Libs\Components;
if(ABSPATH) {
    class FooterComponent{
		private $__ft_args;
        private $__ftr_scripts;
        public function __construct($args= null, $scripts = null){
            $this->__ft_args = $args;
            $this->__ftr_scripts = $scripts;
        }
        private function __to_string(){
            $footer = "<section class='footer display-flex relative'>";
            $footer .= "<footer class='relative'>";
            $footer .= "footer element";
            $footer .= $this->__ft_args;
            $footer .= "</footer>";
            $footer .= "</section>";
            $footer .= $this->__ftr_scripts;
            $footer .= "";
            return $footer;
        }
        public function __toString(){
            return $this->__to_string();
        }
    }
}else {die;}