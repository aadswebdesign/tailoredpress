<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 27-3-2023
 * Time: 22:04
 */
namespace TP_Core\Libs\Components;
if(ABSPATH) {
    class HeadComponent{
 		private $__hd_args;
        public function __construct($args= null){
            $this->__hd_args = $args;
        }
        private function __to_string(){
            $head  = "<head>";
            $head .= $this->__hd_args;
            $head .= "</head>";
            return $head;
        }
        public function __toString(){
            return $this->__to_string();
        }
    }
}else {die;}