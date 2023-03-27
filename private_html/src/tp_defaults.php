<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 25-3-2023
 * Time: 07:24
 */
if(ABSPATH) {
    class tp_defaults{
        public function __construct(){
            $this->__defines();
        }
        private function __defines(){
            define('DEFAULT_THEME_INDEX', 'default_index');
            define('DEFAULT_THEME_PATH', 'TP_Content\\Themes\\default_theme\\');
            //defined('') ? null : define('', '');
            //defined('') ? null : define('', '');
            //defined('') ? null : define('', '');
        }
    }
}else {die;}