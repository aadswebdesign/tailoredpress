<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 24-3-2023
 * Time: 22:46
 */
namespace TP_Content\Themes;
use \tp_auto_loaders;
if(ABSPATH) {
    class themeIndex extends \tp_configs{
        public function __construct() {
            parent::__construct();
            $path = NS_DEFAULT_THEME_PATH;
            $class_name = 'default_index';
            if (defined('NS_CUSTOM_THEME_PATH') && defined('NS_CUSTOM_THEME_INDEX')){
                $path = NS_CUSTOM_THEME_PATH;
                $class_name = NS_CUSTOM_THEME_INDEX;
            }
            $tp = new tp_auto_loaders();
            echo $tp->load_class($class_name,$path);
        }
    }
}else {die;}