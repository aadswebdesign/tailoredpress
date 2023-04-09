<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 20-3-2023
 * Time: 13:26
 */
namespace TP_Core\Libs\Database;
use TP_Core\Libs\Database\Traits\db_credentials;//todo move this out to a configs file
use TP_Core\Traits\utils_methods;
if(ABSPATH) {
    abstract class DB_Abstract_Driver{
        use db_credentials,utils_methods;
        public const ERROR_UNKNOWN_DATABASE = 1049;
        public const PARAM_NULL = 'null';
        public const PARAM_INT = 'int';
        public const PARAM_STR = 'string';
        public const PARAM_BOOL = 'bool';
        private static $__queries = []; //todo
        private $__debug = false; //todo
        private $__conn_id; //todo
        public function __construct($host, $user, $pass, $db, array $options = []) {
            $options += [
                'charset'      => DB_CHARSET,
                'connection_id' => ''
            ];
            $this->__conn_id = $options['connection_id'];
            $this->_connect($host, $user, $pass, $db, $options);
        }
        abstract protected function _connect($host, $user, $pass, $db, array $options = []);
        public static function getGlobalQueryCount() {
            $count = 0;
            foreach (self::getGlobalQueryLog() as $queries) {
                $count += count($queries);
            }
            self::consoleHelper('counted is','true');
            return $count;
        }
        public static function getGlobalQueryLog() {
            return self::$__queries;
        }
        public static function getGlobalQueryTimeSum() {
            $sum = 0;
            foreach (self::$__queries as $instance) {
                foreach ($instance as $query) {
                    $sum += $query[0];
                }
            }
            return $sum;
        }
        public static function getParameterType($parameter) {
            if ($parameter === NULL) {
                return self::PARAM_NULL;
            }
            if (is_bool($parameter)) {
                return self::PARAM_BOOL;
            }
            if (is_int($parameter) || is_float($parameter)) {
                return self::PARAM_INT;
            }
            return self::PARAM_STR;
        }
        abstract public function close();
        public function isClosed() {
            return !$this->isConnected();
        }
        abstract public function isConnected();
        public function isDebug() {
            return $this->__debug;
        }
        public function setDebug($debug = true) {
            $this->__debug = (bool)(int)$debug;
        }
        public function getQueryLog() {
            return self::$__queries[$this->__conn_id];
        }
        public function getQueryCount() {
            return count(self::$__queries[$this->__conn_id]);
        }
        public function getQueryTimeSum() {
            $sum = 0;
            foreach (self::$__queries[$this->__conn_id] as $query) {
                $sum += $query[0];
            }
            return $sum;
        }
        public function fetchAllAssoc($result) {
            $rows = [];
            while ($row = $this->fetchAssoc($result)) {
                $rows[] = $row;
            }
            return $rows;
        }
        abstract public function fetchAssoc($result);
        public function fetchAllRows($result) {
            $rows = [];
            while ($row = $this->fetchRow($result)) {
                $rows[] = $row;
            }
            return $rows;
        }
        abstract public function fetchRow($result);
        public function getNextId($table) {
            $status = $this->fetchAssoc($this->query(
                DB_SELECT . ' auto_increment FROM information_schema.tables WHERE
                table_schema = database() AND
                table_name = :table', [':table' => $table]));
            return empty($status) ? false : (int)$status['auto_increment'];
        }
        public function query($query, array $parameters = []) {
            self::$__queries[$this->__conn_id][] = [0, $query, $parameters, debug_backtrace()];
            $query_time = microtime(TRUE);
            $result = $this->_query($query, $parameters);
            $query_time = round((microtime(TRUE) - $query_time), 7);
            self::$__queries[$this->__conn_id][count(self::$__queries[$this->__conn_id]) - 1][0] = $query_time;
            return $result ?: false;
        }
        abstract protected function _query($query, array $parameters = []);
        abstract public function count($field, $table, $conditions = "", array $parameters = []);
        abstract public function fetchFirstColumn($result, $row = 0);
        abstract public function countRows($result);
        abstract public function countColumns($result);
        abstract public function getLastId();
        abstract public function quote($value);
        abstract public function getServerVersion();
    }
}else {die;}