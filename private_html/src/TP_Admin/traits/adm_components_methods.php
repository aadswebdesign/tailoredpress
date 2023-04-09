<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 31-3-2023
 * Time: 14:53
 */
namespace TP_Admin\traits;
use tp_auto_loaders;
if(ABSPATH) {
    trait adm_components_methods{
        public static function load_component($class_name,$args = null){
            $cpn = NS_ADMIN_COMPONENTS;
            $tp = new tp_auto_loaders();
            if(null !== $tp){
                return $tp->load_class($class_name,$cpn,$args);
            }
            return null;
        }
        public static function head_component(...$args){
            return self::load_component('head_cpn',$args);
        }
        public static function header_component(...$args){
            return self::load_component('header_cpn',$args);
        }
        public static function footer_component(...$args){
            return self::load_component('footer_cpn',$args);
        }
        public static function sidebar_component(...$args){
            return self::load_component('sidebar_cpn',$args);
        }
        public static function overlay_component(...$args){
            return self::load_component('overlay_cpn',$args);
        }
    }
}else {die;}