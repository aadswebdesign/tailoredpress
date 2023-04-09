<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 5-4-2023
 * Time: 20:58
 */
namespace TP_Core\Libs\Database\Traits;
use TP_Core\Libs\Database\DB_Config;
use TP_Core\Libs\Database\DB_Factory;

if(ABSPATH) {
    trait db_connect{ //core_resources_include
        public static function connect(...$db_data){
            @list($db_driver,$db_host,$db_user,$db_name,$db_pass)=$db_data;
            $driver = !empty($db_driver) && $db_driver === 'pdo' && extension_loaded('pdo_mysql') ? DB_Factory::DRIVER_PDO_MYSQL : DB_Factory::DRIVER_MYSQLI;
            DB_Factory::setDefaultDriver($driver);
            if (!empty($db_host) && !empty($db_user) && !empty($db_name)) {
                DB_Factory::registerConfiguration(DB_Factory::getDefaultConnectionId(), [
                    'host'     => $db_host,
                    'user'     => $db_user,
                    'password' => !empty($db_pass) ? $db_pass : '',
                    'database' => $db_name,
                    'charset'  => 'utf8mb4',
                    'debug'    => DB_Factory::isDebug(DB_Factory::getDefaultConnectionId())
                ]);
            }
            DB_Factory::registerConfigurationFromFile('DB_Config','TP_Core\\Libs\\Database\\');//todo
            new DB_Config();
        }//
    }
}else {die;}