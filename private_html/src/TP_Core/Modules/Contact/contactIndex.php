<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 24-3-2023
 * Time: 22:46
 */
namespace TP_Core\Modules\Contact;
if(ABSPATH) {
    class contactIndex{
        public function __construct() {
            echo 'contactIndex with auto loader.';
        }
    }
}else {die;}