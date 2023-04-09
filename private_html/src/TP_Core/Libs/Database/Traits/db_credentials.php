<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 18-3-2023
 * Time: 19:04
 */

namespace TP_Core\Libs\Database\Traits;
if(ABSPATH) {
    trait db_credentials{
        private function pvt_credentials(){
            # DIRECTORY SEPARATOR
            defined('DS') ? null : define('DS','/');
            # DIRECTORY PATH
            defined('BASE_URI') ? null : define('BASE_URI', dirname(__FILE__, 2) .DS);
            # DB_HOST
            defined("DB_HOST") ? null : define("DB_HOST", "localhost");
            # DB_USERNAME
            defined("DB_USERNAME") ? null : define("DB_USERNAME", "root");
            # DB_PASSWORD
            defined("DB_PASSWORD") ? null : define("DB_PASSWORD", "w2v43e72");
            # DB_NAME
            defined("DB_NAME") ? null : define("DB_NAME", "book_db");
            # DB_PORT
            defined('DB_PORT') ? null : define('DB_PORT', 3306);
            # DB_CHARSET
            defined('DB_CHARSET') ? null : define('DB_CHARSET', 'UTF8');//'utf8mb4'
        }
    }
}else {die;}

