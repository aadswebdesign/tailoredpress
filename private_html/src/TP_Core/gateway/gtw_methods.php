<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 8-4-2023
 * Time: 21:03
 */
namespace TP_Core\gateway;
use TP_Core\Traits\core_methods;
if(ABSPATH) {
    trait gtw_methods{
        use core_methods;
        public static $locale;
        /**
         * @param int|string|bool $num
         * @return bool|string
         */
        public static function convertNumberToWord($num = false) {
            $num = str_replace([',', ' '], '', trim($num));
            if (!$num) {
                return FALSE;
            }
            $_locale = self::$locale;
            $num = (int)$num;
            $words = [];
            $list1 = ['', $_locale['gateway_001'], $_locale['gateway_002'], $_locale['gateway_003'], $_locale['gateway_004'], $_locale['gateway_005'], $_locale['gateway_006'], $_locale['gateway_007'], $_locale['gateway_008'], $_locale['gateway_009'], $_locale['gateway_010'],
                $_locale['gateway_011'], $_locale['gateway_012'], $_locale['gateway_013'], $_locale['gateway_014'], $_locale['gateway_015'], $_locale['gateway_016'], $_locale['gateway_017'], $_locale['gateway_018'], $_locale['gateway_019']];
            $list2 = ['', $_locale['gateway_020'], $_locale['gateway_021'], $_locale['gateway_022'], $_locale['gateway_023'], $_locale['gateway_024'], $_locale['gateway_025'], $_locale['gateway_026'], $_locale['gateway_027'], $_locale['gateway_028'], $_locale['gateway_029']];
            $list3 = ['', $_locale['gateway_030'], $_locale['gateway_031'], $_locale['gateway_032'], $_locale['gateway_033'], $_locale['gateway_034'], $_locale['gateway_035'], $_locale['gateway_036'], $_locale['gateway_037'],
                $_locale['$_locale'], $_locale['gateway_039'], $_locale['gateway_040'], $_locale['gateway_041'], $_locale['gateway_042'], $_locale['gateway_043'], $_locale['gateway_044'],
                $_locale['$_locale'], $_locale['gateway_046'], $_locale['gateway_047'], $_locale['gateway_048'], $_locale['gateway_049'], $_locale['gateway_050']];
            $num_length = strlen($num);
            $levels = (int)(($num_length + 2) / 3);
            $max_length = $levels * 3;
            $num = substr('00'.$num, -$max_length);
            $num_levels = str_split($num, 3);
            foreach ($num_levels as $iValue) {
                $levels--;
                $hundreds = (int)($iValue / 100);
                $hundreds = ($hundreds ? ' '.$list1[$hundreds].$_locale['gateway_051'].' ' : '');
                $tens = ($iValue % 100);
                $singles = '';
                if ($tens < 20) {
                    $tens = ($tens ? ' '.$list1[$tens].' ' : '');
                } else {
                    $tens = (int)($tens / 10);
                    $tens = ' '.$list2[$tens].' ';
                    $singles = ($iValue % 10);
                    $singles = ' '.$list1[$singles].' ';
                }
                $words[] = $hundreds.$tens.$singles.(($levels && (int)($iValue)) ? ' '.$list3[$levels].' ' : '');
            }
            $words = str_replace(' ', '', $words);
            return implode($words);
        }
        public static function str_rot47($str) {
            return strtr($str,
                '!"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~',
                'PQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~!"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNO'
            );
        }
        private function __ctrl_lock_file(){
            if (file_exists(CONTROL_LOCK_FILE)) {
                if (time() - filemtime(CONTROL_LOCK_FILE) > gtw_constants::CONTROL_BAN_TIME) {
                    unlink(CONTROL_LOCK_FILE);
                } else {
                    self::redirect(__DIR__."error/index.php?code=401");
                    touch(CONTROL_LOCK_FILE);
                    die;
                }
            }
        }
        public static function anti_flood_count_access() {
            // counting requests and last access time
            $control = [];
            if (is_file(gtw_constants::CONTROL_DB) && filesize(gtw_constants::CONTROL_DB) > 0) {
                $fh = fopen(gtw_constants::CONTROL_DB, "rb");
                $control = array_merge($control, json_encode(fread($fh, filesize(gtw_constants::CONTROL_DB))));
                fclose($fh);
            }
            if (isset($control[USER_IP]) && time() - $control[USER_IP]["t"] < gtw_constants::CONTROL_REQ_TIMEOUT) {
                $control[USER_IP]["c"]++;
            } else {
                $control[USER_IP]["c"] = 1;
            }
            $control[USER_IP]["t"] = time();
            if ($control[USER_IP]["c"] >= gtw_constants::CONTROL_MAX_REQUESTS) {
                // this one did too many requests within a very short period of time
                $fh = fopen(CONTROL_LOCK_FILE, "wb");
                fwrite($fh, USER_IP);
                fclose($fh);
            }
            // write updated control table
            $fh = fopen(gtw_constants::CONTROL_DB, "wb");
            fwrite($fh, serialize($control));
            fclose($fh);
        }
    }
}else {die;}