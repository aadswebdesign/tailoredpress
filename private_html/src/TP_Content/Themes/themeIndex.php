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
			$path = DEFAULT_THEME_PATH;
			$class_name = DEFAULT_THEME_INDEX;
			if (defined('THEME_PATH') && defined('THEME_INDEX')){
				$path = THEME_PATH;
				$class_name = THEME_INDEX;
			}
			$tp = new tp_auto_loaders();
			$tp->load_class($class_name,$path);
        }
    }
}else {die;}