<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 24-3-2023
 * Time: 22:46
 */
namespace TP_Content\Themes\default_theme;
if(ABSPATH) {
    class default_index{
        public function __construct() {
            echo 'default_index with auto loader.';

        }
    }
}else {die;}