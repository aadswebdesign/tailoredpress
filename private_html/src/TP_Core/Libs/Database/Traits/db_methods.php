<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 19-3-2023
 * Time: 22:43
 */
namespace TP_Core\Libs\Database\Traits;
use TP_Core\Libs\Database\DB_Abstract_Driver;
use TP_Core\Libs\Database\DB_Factory;
use TP_Core\Libs\Database\Exception\SelectionException;
use TP_Core\Traits\utils_methods;
if(ABSPATH) {
    trait db_methods{
        use utils_methods;
        private $pvt_pdo;
		public static function get_db_register_shutdown(){
            $output = "<section class='db-block shutdown display-flex relative'><ul class='relative'>";
            if (DB_Factory::isDebug()){
                $log = DB_Abstract_Driver::getGlobalQueryLog();
                foreach ($log as $conn_id => $value) {
                    if (!DB_Factory::isDebug($conn_id)) {
                        unset($log[$conn_id]);
                    }
                }
                $output .= "<li class='relative'>\n";
                $output .= "<dd class='relative'><a href='#queries' class='queries-btn btn btn-primary relative'>View Queries</a></dd>";
                $output .= "</li>\n";
                $output .= "<li class='relative'>\n";
                foreach ($log as $connID => $queries) {
                    $output .= "<dt id='queries' class='well queries-log display-flex relative' style='display:none;'>";
                    $queries_time = 0;
                    $queries_log = '';
                    foreach ($queries as $key => $query) {
                        $queries_time += $query[0];
                        $queries_log .= "<p><strong>#{($key + 1)} Time: {$query[0]}</strong></p>\n";
                        $queries_log .= "<p>{self::strip_input($query[1])}</p>\n";
                    }
                    $output .= "<dt>Total time taken by queries to execute in this connection: <strong>$queries_time</strong> seconds</dt>\n";
                    $output .= "<dt><code>$queries_log</code></dt>\n";
                }
                $output .= "</li>\n";
            }
            $output .= "</ul></section><!-- db-block shutdown -->";
            $output .= "styles below in a stylesheet and not here!";
            $output .= "scripts will handled modular and not with jQuery!";
            return $output;
		}//29 added method todo needs to be edited
        public static function db_register_shutdown(){}//29 original
		public static function db_keys($table){
            static $col_names = [];
            if (empty($col_names[$table])) {
                $res = self::db_query("SHOW COLUMNS FROM $table");
                $col_names = [];
                while ($rows = self::db_array($res)) {
                    $col_names[$table][] = $rows['Field'];
                }
            }
            return (array)$col_names[$table] ? array_map(static function ($k) {
                return $k ?: '';//todo is a gamble
            }, array_flip($col_names[$table])) : [];
        }//110
		public static function db_query($query, $parameters = []){
            return DB_Factory::getConnection('default')->query($query, $parameters);
        }//134
		public static function db_count($field, $table, $conditions = "", $parameters = []){
            return DB_Factory::getConnection('default')->count($field, $table, $conditions, $parameters);
        }//149
		public static function db_result($result, $row){
            return DB_Factory::getConnection('default')->fetchFirstColumn($result, $row);
        }//161
		public static function db_rows($result){
            return DB_Factory::getConnection('default')->countRows($result);
        }//172
		public static function db_array($result){
            return DB_Factory::getConnection('default')->fetchAssoc($result);
        }//183
		public static function db_array_num($result){
            return DB_Factory::getConnection('default')->fetchRow($result);
        }//194
		public static function db_connect($db_host, $db_user, $db_pass, $db_name, $db_port = 3306, $halt_on_error = false){
            $connection_success = true;
            $dbselection_success = true;
            try {
                DB_Factory::connect($db_host, $db_user, $db_pass, $db_name, [
                    'debug' => DB_Factory::isDebug('default'),
                    'port'  => $db_port
                ]);
            } catch (\Exception $e) {
                $connection_success = $e instanceof SelectionException;
                $dbselection_success = false;
                if ($halt_on_error && !$connection_success) {
                    die("<p><strong>Unable to establish connection to MySQL</strong></p>".$e->getCode()." : ".$e->getMessage());
                }
                if ($halt_on_error) {
                    die("<p><strong>Unable to select MySQL database</strong></p>".$e->getCode()." : ".$e->getMessage());
                }
            }
            return [
                'connection_success'  => $connection_success,
                'dbselection_success' => $dbselection_success
            ];
		}//210
		public static function db_custom_connect($db_host, $db_user, $db_pass, $db_name, $db_port, $db_id){
            DB_Factory::registerConfiguration($db_id, [
                'host'     => $db_host,
                'user'     => $db_user,
                'password' => $db_pass,
                'database' => $db_name,
                'port'     => $db_port,
                'charset'  => 'utf8mb4',
                'debug'    => DB_Factory::isDebug($db_id)
            ]);
            return DB_Factory::getConnection($db_id);
        }//247
		public static function db_next_id($table){
            return DB_Factory::getConnection('default')->getNextId($table);
        }//272
		public static function db_last_id(){
            return DB_Factory::getConnection('default')->getLastId();
        }//281
		public static function db_connection(){
            try {
                return DB_Factory::getConnection('default');
            } catch (\Exception $e) {
                ## Do nothing to hide all errors
                ini_set('display_errors', false);
                return null;
            }
        }//290
		//todo to moving!
		//public static function db_query_tree_full(...$string){return '';}//290
		//public static function db_query_insert(...$string){return '';}//
		//public static function db_query_order(...$string){return '';}//
		//public static function db_exists(...$string){return '';}//
		//public static function {return '';}//
		//public static function {return '';}//
		//public static function {return '';}//
		//public static function {return '';}//
    }
}else {die;}