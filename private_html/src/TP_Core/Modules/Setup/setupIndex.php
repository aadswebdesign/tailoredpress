<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 24-3-2023
 * Time: 22:46
 */
namespace TP_Core\Modules\Setup;
if(ABSPATH) {
    class setupIndex{
        public function __construct() {
            echo 'setupIndex with auto loader.';
        }
    }
}else {die;}