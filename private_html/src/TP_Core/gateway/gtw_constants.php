<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 8-4-2023
 * Time: 20:54
 */
namespace TP_Core\gateway;
if(ABSPATH) {
    class gtw_constants{
        public const SCRIPT_ROOT = __DIR__;
        // number of allowed page requests for the user
        public const CONTROL_MAX_REQUESTS = 2;
        // time interval to start counting page requests (seconds)
        public const CONTROL_REQ_TIMEOUT = 1;
        // seconds to punish the user who has exceeded in doing requests
        public const CONTROL_BAN_TIME = 120 * 120;
        // writable directory to keep script data
        public const SCRIPT_TMP_DIR = self::SCRIPT_ROOT."/flood";
        public const CONTROL_DB = self::SCRIPT_TMP_DIR."/ctrl";
        public const CONTROL_LOCK_DIR = self::SCRIPT_TMP_DIR."/lock";
        public function __construct(){
            define('USER_IP', 'temporary');//todo
            define("CONTROL_LOCK_FILE", self::CONTROL_LOCK_DIR."/".md5(USER_IP));
        }
    }
}else {die;}