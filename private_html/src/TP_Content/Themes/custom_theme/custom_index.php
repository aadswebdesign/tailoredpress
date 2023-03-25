<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 24-3-2023
 * Time: 22:46
 */
namespace TP_Content\Themes\custom_theme;
if(ABSPATH) {
    class custom_index{
        public function __construct() {
            echo 'custom_index with auto loader.';

        }
    }
}else {die;}