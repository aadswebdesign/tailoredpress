<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 25-3-2023
 * Time: 07:24
 */
if(ABSPATH) {
    class tp_configs extends tp_defaults {
        public function __construct(){
            parent::__construct();
            $this->__configs();
        }
        private function __configs(){
            if(true === $this->TP_ALL_STEPS_DONE){
                $this->TP_HAS_BEEN_SETUP = true;
            }
            //define('THEME_INDEX', 'custom_index');
            //define('THEME_PATH', 'TP_Content\\Themes\\custom_theme\\');
            //defined('') ? null : define('', '');
            //defined('') ? null : define('', '');
            //defined('') ? null : define('', '');
        }
    }
}else {die;}