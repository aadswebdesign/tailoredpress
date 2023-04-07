<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 27-3-2023
 * Time: 22:04
 */
namespace TP_Content\Themes\custom_theme\components;
use TP_Core\Libs\Components\FooterComponent;
if(ABSPATH) {
    class footer_cpn extends FooterComponent{
		private $__ft_args;
        public function __construct($args= null){
            parent::__construct();
            $this->__ft_args = $args;
        }
        private function __to_string(){
            $html = "<section class='footer display-flex relative'>";//or static
            $html .= "<footer class='relative'>";
            $html .= "footer element";
            $html .= "</footer>";
            $html .= "</section>";
            $html .= implode(' ',$this->__ft_args);
            $html .= "";
            return $html;
        }
        public function __toString(){
            return $this->__to_string();
        }
    }
}else {die;}