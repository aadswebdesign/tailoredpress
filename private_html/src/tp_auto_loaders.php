<?php

/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 24-3-2023
 * Time: 22:36
 */
if(!defined('ABSPATH')) { define( 'ABSPATH', __DIR__ . '/' );}
if(ABSPATH) {
    class tp_auto_loaders{
        public function dynamic_classes($namespace,$class_dir, ...$args):string{
            $located = '';
            foreach ( (array) $class_dir as $class ) {
                if ( ! $class ) { continue;}
                $class_name = $namespace.$class;
                if(class_exists($class_name)){
                    $located = new $class_name($args);
                }
            }
            return $located;
        }
        public function load_class($class_name,$namespace, $class_args = null){
            $located = '';
            if(!isset($class_name,$namespace)){
                throw new \RuntimeException('A class_name and namespace, is needed to have classes dynamically loaded!');
            }
            $class_name = $namespace.$class_name;
            if(class_exists($class_name)){ $located = new $class_name($class_args);}
            return $located;
        }//added method
    }
}else {die;}