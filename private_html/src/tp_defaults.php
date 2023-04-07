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
            define('TP_CONTENT', ABSPATH . 'TP_Content/');
            define('TP_THEMES', TP_CONTENT .'Themes/');
            define('DEFAULT_THEME', TP_THEMES . 'default_theme/');
            define('DEFAULT_ASSETS', DEFAULT_THEME . 'assets/');
            define('DEFAULT_GRAPHICS', DEFAULT_ASSETS . 'graphics/');
            define('DEFAULT_SCRIPTS', DEFAULT_ASSETS . 'scripts/');
            define('DEFAULT_STYLES', DEFAULT_ASSETS . 'styles/');
            define('DEFAULT_ICONS', DEFAULT_GRAPHICS . 'icons/');
            define('DEFAULT_IMAGES', DEFAULT_GRAPHICS . 'images/');
            //namespaces
            define('NS_DEFAULT_THEME_PATH', 'TP_Content\\Themes\\default_theme\\');
            define('NS_DEFAULT_COMPONENTS', NS_DEFAULT_THEME_PATH .'components\\');
            //defined('') ? null : define('', '');
            //defined('') ? null : define('', '');
            defined('NS_TP_CORE') ? null : define('NS_TP_CORE', 'TP_Core\\');
            //defined('') ? null : define('', '');
            //defined('') ? null : define('', '');
        }
    }
}else {die;}