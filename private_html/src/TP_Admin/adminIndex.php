<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 24-3-2023
 * Time: 22:46
 */
namespace TP_Admin;
use tp_auto_loaders;
if(ABSPATH) {
    class adminIndex extends \tp_configs {
        public function __construct() {
            parent::__construct();
            echo 'adminIndex with auto loader.';
        }
    }
}else {die;}