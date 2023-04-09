<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 27-3-2023
 * Time: 22:04
 */
namespace TP_Content\Themes\default_theme\components;
use TP_Core\Libs\Components\HeadComponent;
if(ABSPATH) {
    class head_cpn extends HeadComponent{
 		private $__hd_args;
        public function __construct($args= null){
            parent::__construct();
            $this->__hd_args = $args;
        }
        private function __to_string(){
            $head  = "<head>";
            $head .= implode(' ',$this->__hd_args);
            $head .= "</head>";
            return $head;
        }
        public function __toString(){
            return $this->__to_string();
        }
    }
}else {die;}