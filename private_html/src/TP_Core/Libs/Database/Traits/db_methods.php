<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 19-3-2023
 * Time: 22:43
 */
namespace TP_Core\Libs\Database\Traits;
//use TP_Core\src\Libs\DB_Manager\DB_Handler;
//use PDO;
if(ABSPATH) {
    trait db_methods{
        private $pvt_pdo;
		public static function db_register_shutdown(){return '';}//29 added method
		public static function db_keys($table){return '';}//110
		public static function db_query($query, $parameters = []){return '';}//134
		public static function db_count($field, $table, $conditions = "", $parameters = []){return '';}//149
		public static function db_result(...$result){return '';}//161
		public static function db_rows($result){return '';}//172
		public static function db_array($result){return '';}//183
		public static function db_array_num($result){return '';}//194
		public static function db_connect($db_host, $db_user, $db_pass, $db_name, $db_port = 3306, $halt_on_error = false){return '';}//210
		public static function db_custom_connect($db_host, $db_user, $db_pass, $db_name, $db_port, $dbid){return '';}//247
		public static function db_next_id($table){return '';}//272
		public static function db_last_id(){return '';}//281
		public static function db_connection(){return '';}//290
		//todo to moved!
		public static function db_query_tree_full(...$string){return '';}//290
		public static function db_query_insert(...$string){return '';}//
		public static function db_query_order(...$string){return '';}//
		public static function db_exists(...$string){return '';}//
		//public static function {return '';}//
		//public static function {return '';}//
		//public static function {return '';}//
		//public static function {return '';}//















		
		
		
    }
}else {die;}