<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 20-3-2023
 * Time: 18:26
 */
namespace TP_Core\Libs\Database\Drivers;
use TP_Core\Libs\Database\Exception\ConnectionException;
use TP_Core\Libs\Database\Exception\SelectionException;
use TP_Core\Libs\Database\DB_Abstract_Driver;
use TP_Core\Traits\utils_methods;
if(ABSPATH) {
    class DB_MYSQLI extends DB_Abstract_Driver{
        use utils_methods;
        private $pvt_connection;
        public function close() {
            if ($this->isConnected()) {
                mysqli_close($this->pvt_connection);
            }
        }
        public function isConnected() {
            return is_resource($this->pvt_connection);
        }
        public function count($field, $table, $conditions = "", array $parameters = []) {
            $cond = ($conditions ? " WHERE ".$conditions : "");
            $sql = "SELECT COUNT".$field." FROM ".$table.$cond;
            $result = $this->query($sql, $parameters);
            return $result ? $this->fetchFirstColumn($result) : false;
        }
        public function fetchFirstColumn($result, $row = 0) {
            if($result instanceof \mysqli_result){
                $result->data_seek($row) ;
            }
            $value = mysqli_fetch_row($result);
            return $value[0] ?? false;
            //return mysqli_fetch_row($result) ? mysqli_fetch_row($result): FALSE;
        }
        public function countRows($result) {
            if (is_object($result)) {
                return $result->num_rows ?: 0;
            }
            return 0;
        }
        public function countColumns($result) {
            if (is_object($result)) {
                return $result->field_count ?: 0;
            }
            return 0;
        }
        public function fetchAssoc($result) {
            if ($result) {
                return mysqli_fetch_assoc($result);
            }
            return false;
        }
        public function fetchRow($result) {
            $row = mysqli_fetch_row($result);
            return $row ?: false;
        }
        public function getLastId() {
            return (int)mysqli_insert_id($this->pvt_connection);
        }
        public function getServerVersion() {
            return mysqli_get_server_info($this->pvt_connection);
        }
        protected function _connect($host, $user, $pass, $db, array $options = []) {
            if ($this->pvt_connection === NULL) {
                $options += [
                    'charset' => DB_CHARSET,
                    'port'    => DB_PORT
                ];
                $this->pvt_connection = mysqli_connect($host, $user, $pass, $db, $options['port']);
                if (!$this->pvt_connection) {
                    throw new ConnectionException(mysqli_error($this->pvt_connection), mysqli_errno($this->pvt_connection));
                }
                mysqli_set_charset($this->pvt_connection, $options['charset']);
                if (!@mysqli_select_db($this->pvt_connection, "(string)$db")) {
                    throw new SelectionException(mysqli_error($this->pvt_connection), mysqli_errno($this->pvt_connection));
                }
            }
        }
        protected function _query($query, array $parameters = []) {
            if ($parameters) {
                foreach ($parameters as $k => $parameter) {
                    $parameters[$k] = $this->quote($parameter);
                }
                $query = strtr($query, $parameters);
            }
            $result = mysqli_query($this->pvt_connection, $query);
            if (!$result) {
                self::consoleHelper($this->pvt_connection." @ ",$query);
                trigger_error(mysqli_error($this->pvt_connection)." @ ".$query, E_USER_NOTICE);
            }
            return $result ?: false;
        }
        public function quote($value) {
            $type = self::getParameterType($value);
            if (self::PARAM_NULL === $type) {return 'NULL';}
            if (self::PARAM_BOOL === $type) { return $value ? 'true' : 'false';}
            if (self::PARAM_INT === $type) { return $value;}
            return "'".mysqli_real_escape_string($this->pvt_connection, (string)$value)."'";
        }
    }
}else {die;}