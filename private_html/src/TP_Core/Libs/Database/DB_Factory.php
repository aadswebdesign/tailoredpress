<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 20-3-2023
 * Time: 19:10
 */
namespace TP_Core\Libs\Database;

use TP_Core\Libs\Database\Drivers\DB_PDO;
use TP_Core\Libs\Database\Drivers\DB_MYSQLI;
use TP_Core\Libs\Database\Exception\UndefinedConfigurationException;
use TP_Core\Traits\core_methods;
if(ABSPATH) {
    class DB_Factory{
        use core_methods;
        public const DRIVER_MYSQLI = 'mysqli';
        public const DRIVER_PDO_MYSQL = 'pdo_mysql';
        public const DRIVER_DEFAULT = self::DRIVER_PDO_MYSQL;

        private static $pvt_defaultDriver = self::DRIVER_DEFAULT;
        private static $pvt_debug = [];
        private static $pvt_driver_classes = [
            self::DRIVER_MYSQLI    => DB_MYSQLI::class,
            self::DRIVER_PDO_MYSQL => DB_PDO::class
        ];
        private static $pvt_configs = [];
        private static $pvt_default_conn_id = 'default';
        private static $pvt_connections = [];

        public static function registerDriverClass($id, $fullClassName) {
            if (is_subclass_of($fullClassName, DB_Abstract_Driver::class)
                && !isset(self::$pvt_driver_classes[$id])
            ) {
                self::$pvt_driver_classes[$id] = $fullClassName;
            }
        }
        public static function registerConfigurationFromFile($class,$namespace,$args) {
            if (is_file($class)) {
                //$pvt_configs = require $file;
                $pvt_configs = self::autoload_class($class,$namespace,$args);
                if (is_array($pvt_configs)) {
                    self::registerConfigurations($pvt_configs);
                }
                // TODO Exception otherwise
            }
            // TODO Exception otherwise
        }
        public static function registerConfigurations($configurations) {
            foreach ($configurations as $id => $configuration) {
                self::registerConfiguration($id, $configuration);
            }
        }
        public static function registerConfiguration($id, array $configuration) {
            $lowerCaseID = strtolower($id);
            if (!isset(self::$pvt_configs[$lowerCaseID])) {
                $configuration += ['debug' => self::isDebug($id)];
                self::$pvt_configs[$lowerCaseID] = new DB_Config($configuration);
            }
        }
        public static function isDebug($conn_id = null) {
            if ($conn_id) {
                return (isset(self::$pvt_connections[$conn_id])
                    ? self::$pvt_connections[$conn_id]->isDebug()
                    : (self::$pvt_debug === true
                        || (is_array(self::$pvt_debug) && in_array($conn_id, self::$pvt_debug,true))));
            }
            $conn_ids = array_unique(array_merge(
                is_array(self::$pvt_debug) ? self::$pvt_debug : [], array_keys(self::$pvt_connections)));
            /**@var $connections DB_Abstract_Driver */
            foreach ($conn_ids as $k => $id) {
                if (isset(self::$pvt_connections[$id]) && !self::$pvt_connections[$id]->isDebug()) {
                    unset($conn_ids[$k]);
                }
            }
            return (bool)$conn_ids;
        }
        public static function getConnection($id = null) {
            $id = strtolower($id ?: self::getDefaultConnectionId());
            if (!isset(self::$pvt_configs[$id])) {
                throw new UndefinedConfigurationException("Unknown configuration id: ".$id);
            }
            if (isset(self::$pvt_connections[$id]) && self::$pvt_connections[$id]->isConnected()) {
                return self::$pvt_connections[$id];
            }
            $conf = self::$pvt_configs[$id];
            self::connect($conf->getHost(), $conf->getUser(), $conf->getPassword(), $conf->getDatabase(), [
                'driver'       => $conf->getDriver(),
                'conn_id' => $id,
                'debug'        => $conf->isDebug()
            ]);
            return self::$pvt_connections[$id];
        }
        public static function getDefaultConnectionId() {
            return self::$pvt_default_conn_id;
        }
        public static function connect($host, $user, $password, $db, array $options = []) {
            $configuration = new DB_Config();
            /** @noinspection PhpUndefinedMethodInspection */
            $options += array(
                'charset'      => $configuration->getCharset(),
                'driver'       => $configuration->getDriver(),
                'conn_id' => self::getDefaultConnectionId(),
                'debug'        => $configuration->isDebug()
            );
            $id = strtolower($options['conn_id']);
            if (empty(self::$pvt_connections[$id])) {
                if (!isset(self::$pvt_configs[$id])) {
                    self::registerConfiguration($id, $configuration->toArray());
                }
                if (!isset(self::$pvt_connections[$id]) || self::$pvt_connections[$id]->isClosed()) {
                    $class = self::getDriverClass(strtolower($options['driver']));
                    /**@var $connection DB_Abstract_Driver */
                    $connection = new $class($host, $user, $password, $db, $options);
                    $connection->setDebug($options['debug']);
                    self::$pvt_connections[$id] = $connection;
                }
            }
            return self::$pvt_connections[$id];
        }
        public static function getDriverClass($id = NULL) {
            if ($id === NULL) {
                $id = self::getDefaultDriver();
            }
            return self::$pvt_driver_classes[$id] ?? null;
        }
        public static function getDefaultDriver() {
            return self::$pvt_defaultDriver;
        }
        public static function setDefaultDriver($defaultDriver) {
            self::$pvt_defaultDriver = $defaultDriver;
        }
    }
}else {die;}