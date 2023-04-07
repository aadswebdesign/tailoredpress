<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 4-4-2023
 * Time: 19:03
 */
namespace TP_Admin;
if(ABSPATH) {
    class adm_configs{
        public function __construct(){
            define('TP_Admin',ABSPATH . 'TP_Admin/');
            define('ADM_ASSETS', TP_Admin . 'assets/');
            define('ADM_GRAPHICS', ADM_ASSETS . 'graphics/');
            define('ADM_SCRIPTS', ADM_ASSETS . 'scripts/');
            define('ADM_STYLES', ADM_ASSETS . 'styles/');
            define('ADM_ICONS', ADM_GRAPHICS . 'icons/');
            define('ADM_IMAGES', ADM_GRAPHICS . 'images/');

            define('NS_ADMIN_PATH', 'TP_ADMIN\\');
            define('NS_ADMIN_COMPONENTS', NS_ADMIN_PATH . 'components\\');
            //define('', '');
            //todo 's
            define('LOCALE', '');
            define('LOCALESET', '');
            //define('', '');
            //define('', '');
            //define('', '');
        }
    }
}else {die;}

