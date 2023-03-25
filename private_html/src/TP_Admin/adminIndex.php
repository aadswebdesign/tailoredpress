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
            if(true === $this->TP_HAS_BEEN_SETUP){
                echo 'adminIndex with auto loader.';
            }else{
                $tp = new tp_auto_loaders();
                $tp->load_class('setupIndex','TP_Setup\\');
            }
        }
    }
}else {die;}