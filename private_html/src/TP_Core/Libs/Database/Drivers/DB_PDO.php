<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 19-3-2023
 * Time: 19:42
 */
namespace TP_Core\Libs\Database\Drivers;
use PDO;
use PDOException;
use PDOStatement;
use TP_Core\Libs\Database\Exception\SelectionException;
use TP_Core\Libs\Database\DB_Abstract_Driver;
use TP_Core\Traits\utils_methods;
if(ABSPATH) {
    class DB_PDO extends DB_Abstract_Driver{
        use utils_methods;
        protected $ptt_pdo;
        private static $paramTypeMap = [
            self::PARAM_INT  => PDO::PARAM_INT,
            self::PARAM_BOOL => PDO::PARAM_BOOL,
            self::PARAM_STR  => PDO::PARAM_STR,
            self::PARAM_NULL => PDO::PARAM_NULL
        ];
        private $__connection;
        public function close() {
            $this->__connection = null;
        }
        public function isConnected() {
            return $this->__connection instanceof PDO;
        }
        public function _query($query, array $parameters = []) {
            try {
                $result = null;
                if($this->__connection instanceof PDO){
                    $result = $this->__connection->prepare($query);
                }
                foreach ($parameters as $key => $parameter) {
                    $result->bindValue($key, $parameter, self::$paramTypeMap[self::getParameterType($parameter)]);
                }
                $result->execute();
                return $result;
            } catch (PDOException $e) {
                self::consoleHelper("$query Stack Trace",$e->getTraceAsString() ."Error Nature: {$e->getMessage()}" . E_USER_NOTICE);//. $tb_fields.
                //trigger_error("Query Error: ".$query."<br/>Stack Trace: ".$e->getTraceAsString()."<br/>Error Nature: ".$e->getMessage(), E_USER_NOTICE);
                return false;
            }
        }
        public function count($field, $table, $conditions = "", array $parameters = []) {
            $cond = ($conditions ? " WHERE ".$conditions : "");
            $sql = "SELECT COUNT".$field." FROM ".$table.$cond;
            $statement = $this->query($sql, $parameters);
            $_statement = null;
            if($statement instanceof PDOStatement){
                $_statement = $statement;
            }
            return $_statement ? $_statement->fetchColumn() : false;
        }
        public function fetchFirstColumn($result, $row = 0) {
            //seek
            if ($result instanceof PDOStatement) {
                for ($i = 0; $i < $row; $i++) {
                    $result->fetchColumn();
                }
                //returns false when an error occurs
                return $result->fetchColumn();
            }
            return null;
        }
        public function countRows($result) {
            if ($result instanceof PDOStatement) {
                return $result->rowCount();
            }
            return null;
        }
        public function countColumns($result) {
            if ($result instanceof PDOStatement) {
                return $result->columnCount();
            }
            return null;
        }
        public function fetchAssoc($result) {
            if ($result instanceof PDOStatement) {
                $result->setFetchMode(PDO::FETCH_ASSOC);
                return $result->fetch();
            }
            return null;
        }
        public function fetchRow($result) {
            if ($result instanceof PDOStatement) {
                $result->setFetchMode(PDO::FETCH_NUM);
                return $result->fetch();
            }
            return null;
        }
        public function getLastId(){
            if($this->__connection instanceof PDO){
                return (int)$this->__connection->lastInsertId();
            }
            return null;
        }
        public function quote($value) {
            if($this->__connection instanceof PDO){
                return (int)$this->__connection->quote($value);
            }
            return null;
        }
        public function getServerVersion() {
            if($this->__connection instanceof PDO){
                return $this->__connection->getAttribute(PDO::ATTR_SERVER_VERSION);
            }
            return null;
        }
        protected function _connect($host, $user, $pass, $db, array $options = []) {
            if ($this->__connection === null) {
                $options += ['charset' => DB_CHARSET,'port' => DB_PORT];
                try {
                    $this->ptt_pdo = $this->__connection = new PDO("mysql:host=".$host.";dbname=".$db.";port=".$options['port'].";charset=".$options['charset'], $user, $pass,
                        [
                            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES '.$options['charset'].', sql_mode=ALLOW_INVALID_DATES'
                        ]
                    );
                    $this->ptt_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $this->ptt_pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, TRUE);
                    $this->ptt_pdo->setAttribute(PDO::ATTR_PERSISTENT, FALSE);
                } catch (PDOException $error) {
                    throw $error->getCode() === self::ERROR_UNKNOWN_DATABASE
                        ? new SelectionException($error->getMessage(), $error->getCode(), $error)
                        : new \ErrorException($error->getMessage(), $error->getCode(), 1, debug_backtrace()[1]['file']); //new ConnectionException($error->getMessage(), $error->getCode(), $error);
                }
            }
        }
    }
}else {die;}