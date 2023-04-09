<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 20-3-2023
 * Time: 18:58
 */
namespace TP_Core\Libs\Database;
use BadMethodCallException;
use TP_Core\Libs\Database\Traits\db_credentials;
if(ABSPATH) {
    class DB_Config{
        use db_credentials;
        private $__config;
        public function __construct(array $config = []) {
            $this->__config = $config + [//todo
                    'host'     => '',
                    'database' => '',
                    'user'     => '',
                    'password' => '',
                    'charset'  => 'utf8mb4',
                    'driver'   => DB_Factory::getDefaultDriver(),
                    'debug'    => false
                ];
            $this->__config['driver'] = strtolower($this->__config['driver']);
        }
        public function isDebug() {
            return (bool)$this->__config['debug'];
        }
        public function __call($method, $arguments) {
            $method = strtolower($method);
            if (strpos($method, 'get') !== 0) {
                throw new BadMethodCallException(sprintf("This method does not exist: '%s' ", $method));
            }
            $index = substr($method, 3);
            if (!isset($this->__config[$index])) {
                throw new BadMethodCallException(sprintf("This method does not exist: '%s' ", $method));
            }
            return $this->__config[$index];
        }
        public function toArray() {
            return $this->__config;
        }
    }
}else {die;}
