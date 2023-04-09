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
            define('CUSTOM_THEME', TP_THEMES . 'custom_theme/');
            define('CUSTOM_ASSETS', CUSTOM_THEME . 'assets/');
            define('CUSTOM_GRAPHICS', CUSTOM_ASSETS . 'graphics/');
            define('CUSTOM_SCRIPTS', CUSTOM_ASSETS . 'scripts/');
            define('CUSTOM_STYLES', CUSTOM_ASSETS . 'styles/');
            define('CUSTOM_ICONS', CUSTOM_GRAPHICS . 'icons/');
            define('CUSTOM_IMAGES', CUSTOM_GRAPHICS . 'images/');

            //namespaces
            define('NS_CUSTOM_THEME_INDEX', 'custom_index');
            define('NS_CUSTOM_THEME_PATH', 'TP_Content\\Themes\\custom_theme\\');
            define('NS_CUSTOM_COMPONENTS', NS_CUSTOM_THEME_PATH .'components\\');
            //defined('') ? null : define('', '');
            //defined('') ? null : define('', '');
            //defined('') ? null : define('', '');
            //define('CUSTOM_ASSETS', CUSTOM_THEME_PATH. '/custom_assets');
        }
    }
}else {die;}