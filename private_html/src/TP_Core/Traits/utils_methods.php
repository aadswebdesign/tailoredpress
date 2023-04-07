<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 26-3-2023
 * Time: 17:17
 */
namespace TP_Core\src\Traits;
if(ABSPATH) {
    trait utils_methods{ //from core_methods
        public static function clean_url($url){
            $bad_entities = ["&", "\"", "'", '\"', "\'", "<", ">", "", "", "*"];
            $safe_entities = ["&amp;", "", "", "", "", "", "", "", "", ""];
            return str_replace($bad_entities, $safe_entities, $url);
        }
        public static function strip_input($text){
            if (!is_array($text) && !is_null($text)) {
                return str_replace('\\', '&#092;', htmlspecialchars(stripslashes(trim($text)), ENT_QUOTES));
            }
            if (is_array($text) && !is_null($text)) {
                foreach ($text as $i => $item) {
                    $text[$i] = self::strip_input($item);
                }
            }
            return $text;
        }
        public static function strip_get($check_url){
            if (is_array($check_url)) {
                foreach ($check_url as $value) {
                    if (self::strip_get($value) === true) {
                        return true;
                    }
                }
            } else {
                $check_url = str_replace(["\"", "\'"], ["", ""], urldecode($check_url));
                if (preg_match("/<[^<>]+>/", $check_url)) {
                    return true;
                }
            }
            return false;
        }
        public static function strip_filename($filename){
            $patterns = [
                '/\s+/'              => '_',
                '/[^a-z0-9_-]|^\W/i' => '',
                '/([_-])\1+/'        => '$1'
            ];
            return preg_replace(array_keys($patterns), $patterns, strtolower($filename)) ?: (string)time();
        }
        public static function get_http_response_code($url){
            if (function_exists('curl_init')) {
                $handle = curl_init($url);
                curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
                curl_exec($handle);
                $http_code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
                curl_close($handle);
                return $http_code;
            }
            stream_context_set_default([
                'ssl' => [
                    'verify_peer'      => FALSE,
                    'verify_peer_name' => FALSE
                ],
            ]);
            $headers = @get_headers($url);
            return substr($headers[0], 9, 3);
        }
        public static function set_status_header($code = 200){
            if (headers_sent()) { return false;}
            $protocol = $_SERVER['SERVER_PROTOCOL'];
            if ('HTTP/1.1' !== $protocol && 'HTTP/1.0' !== $protocol) {
                $protocol = 'HTTP/1.0';
            }
            $desc = [
                100 => 'Continue',101 => 'Switching Protocols',102 => 'Processing',200 => 'OK',201 => 'Created',202 => 'Accepted',
                203 => 'Non-Authoritative Information', 204 => 'No Content',205 => 'Reset Content',206 => 'Partial Content',
                207 => 'Multi-Status', 226 => 'IM Used', 300 => 'Multiple Choices',301 => 'Moved Permanently',302 => 'Found',
                303 => 'See Other',304 => 'Not Modified',305 => 'Use Proxy', 306 => 'Reserved',307 => 'Temporary Redirect',
                400 => 'Bad Request',401 => 'Unauthorized',402 => 'Payment Required',403 => 'Forbidden',404 => 'Not Found', 405 => 'Method Not Allowed',
                406 => 'Not Acceptable',407 => 'Proxy Authentication Required',408 => 'Request Timeout',409 => 'Conflict',410 => 'Gone',
                411 => 'Length Required',412 => 'Precondition Failed',413 => 'Request Entity Too Large',414 => 'Request-URI Too Long',
                415 => 'Unsupported Media Type',416 => 'Requested Range Not Satisfiable',417 => 'Expectation Failed',422 => 'Unprocessable Entity',
                423 => 'Locked',424 => 'Failed Dependency',426 => 'Upgrade Required',500 => 'Internal Server Error',501 => 'Not Implemented',
                502 => 'Bad Gateway',503 => 'Service Unavailable',504 => 'Gateway Timeout',505 => 'HTTP Version Not Supported',
                506 => 'Variant Also Negotiates',507 => 'Insufficient Storage',510 => 'Not Extended'
            ];
            $desc = !empty($desc[$code]) ? $desc[$code] : '';
            header("$protocol $code $desc");
            return true;
        }
        public static function php_entities($text){
            return str_replace('\\', '&#092;', htmlspecialchars($text, ENT_QUOTES));
        }
        public static function trim_link($text, $length){
            if (strlen($text) > $length) {
                if (function_exists('mb_substr')) {
                    $text = mb_substr($text, 0, ($length - 3), 'UTF-8')."...";
                } else { $text = substr($text, 0, ($length - 3))."..."; }
            }
            return $text;
        }
        public static function tp_first_words($text, $limit, $suffix = '&hellip;'){
            $text = preg_replace('/[\r\n]+/', '', $text);
            return preg_replace('~^(\s*\w+'.str_repeat('\W+\w+', $limit - 1).'(?(?=[?!:;.])[[:punct:]]\s*))\b(.+)$~isxu', '$1'.$suffix, strip_tags($text));
        }
        public static function trim_text($str, $length = 300){
            $_str_l = strlen($str);
            for ($i = $length; $i <= $_str_l; $i++) {
                $spacetest = substr("(string)$str", $i, 1);
                if ($spacetest === " ") {
                    $spaceok = substr("(string)$str", 0, $i);
                    return ($spaceok."...");
                }
            }
            return ($str);
        }
        public static function normalize($value){
            $table = [
                '&amp;' => 'and', '@' => 'at', '©' => 'c', '®' => 'r', 'À' => 'a', '(' => '', ')' => '', '.' => '',
                'Á'     => 'a', 'Â' => 'a', 'Ä' => 'a', 'Å' => 'a', 'Æ' => 'ae', 'Ç' => 'c',
                'È'     => 'e', 'É' => 'e', 'Ë' => 'e', 'Ì' => 'i', 'Í' => 'i', 'Î' => 'i',
                'Ï'     => 'i', 'Ò' => 'o', 'Ó' => 'o', 'Ô' => 'o', 'Õ' => 'o', 'Ö' => 'o',
                'Ø'     => 'o', 'Ù' => 'u', 'Ú' => 'u', 'Û' => 'u', 'Ü' => 'u', 'Ý' => 'y',
                'ß'     => 'ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ä' => 'a', 'å' => 'a',
                'æ'     => 'ae', 'ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e',
                'ì'     => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ò' => 'o', 'ó' => 'o',
                'ô'     => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u',
                'û'     => 'u', 'ü' => 'u', 'ý' => 'y', 'þ' => 'p', 'ÿ' => 'y', 'Ā' => 'a',
                'ā'     => 'a', 'Ă' => 'a', 'ă' => 'a', 'Ą' => 'a', 'ą' => 'a', 'Ć' => 'c',
                'ć'     => 'c', 'Ĉ' => 'c', 'ĉ' => 'c', 'Ċ' => 'c', 'ċ' => 'c', 'Č' => 'c',
                'č'     => 'c', 'Ď' => 'd', 'ď' => 'd', 'Đ' => 'd', 'đ' => 'd', 'Ē' => 'e',
                'ē'     => 'e', 'Ĕ' => 'e', 'ĕ' => 'e', 'Ė' => 'e', 'ė' => 'e', 'Ę' => 'e',
                'ę'     => 'e', 'Ě' => 'e', 'ě' => 'e', 'Ĝ' => 'g', 'ĝ' => 'g', 'Ğ' => 'g',
                'ğ'     => 'g', 'Ġ' => 'g', 'ġ' => 'g', 'Ģ' => 'g', 'ģ' => 'g', 'Ĥ' => 'h',
                'ĥ'     => 'h', 'Ħ' => 'h', 'ħ' => 'h', 'Ĩ' => 'i', 'ĩ' => 'i', 'Ī' => 'i',
                'ī'     => 'i', 'Ĭ' => 'i', 'ĭ' => 'i', 'Į' => 'i', 'į' => 'i', 'İ' => 'i',
                'ı'     => 'i', 'Ĳ' => 'ij', 'ĳ' => 'ij', 'Ĵ' => 'j', 'ĵ' => 'j', 'Ķ' => 'k',
                'ķ'     => 'k', 'ĸ' => 'k', 'Ĺ' => 'l', 'ĺ' => 'l', 'Ļ' => 'l', 'ļ' => 'l',
                'Ľ'     => 'l', 'ľ' => 'l', 'Ŀ' => 'l', 'ŀ' => 'l', 'Ł' => 'l', 'ł' => 'l',
                'Ń'     => 'n', 'ń' => 'n', 'Ņ' => 'n', 'ņ' => 'n', 'Ň' => 'n', 'ň' => 'n',
                'ŉ'     => 'n', 'Ŋ' => 'n', 'ŋ' => 'n', 'Ō' => 'o', 'ō' => 'o', 'Ŏ' => 'o',
                'ŏ'     => 'o', 'Ő' => 'o', 'ő' => 'o', 'Œ' => 'oe', 'œ' => 'oe', 'Ŕ' => 'r',
                'ŕ'     => 'r', 'Ŗ' => 'r', 'ŗ' => 'r', 'Ř' => 'r', 'ř' => 'r', 'Ś' => 's',
                'ś'     => 's', 'Ŝ' => 's', 'ŝ' => 's', 'Ş' => 's', 'ş' => 's', 'Š' => 's',
                'š'     => 's', 'Ţ' => 't', 'ţ' => 't', 'Ť' => 't', 'ť' => 't', 'Ŧ' => 't',
                'ŧ'     => 't', 'Ũ' => 'u', 'ũ' => 'u', 'Ū' => 'u', 'ū' => 'u', 'Ŭ' => 'u',
                'ŭ'     => 'u', 'Ů' => 'u', 'ů' => 'u', 'Ű' => 'u', 'ű' => 'u', 'Ų' => 'u',
                'ų'     => 'u', 'Ŵ' => 'w', 'ŵ' => 'w', 'Ŷ' => 'y', 'ŷ' => 'y', 'Ÿ' => 'y',
                'Ź'     => 'z', 'ź' => 'z', 'Ż' => 'z', 'ż' => 'z', 'Ž' => 'z', 'ž' => 'z',
                'ſ'     => 'z', 'Ə' => 'e', 'ƒ' => 'f', 'Ơ' => 'o', 'ơ' => 'o', 'Ư' => 'u',
                'ư'     => 'u', 'Ǎ' => 'a', 'ǎ' => 'a', 'Ǐ' => 'i', 'ǐ' => 'i', 'Ǒ' => 'o',
                'ǒ'     => 'o', 'Ǔ' => 'u', 'ǔ' => 'u', 'Ǖ' => 'u', 'ǖ' => 'u', 'Ǘ' => 'u',
                'ǘ'     => 'u', 'Ǚ' => 'u', 'ǚ' => 'u', 'Ǜ' => 'u', 'ǜ' => 'u', 'Ǻ' => 'a',
                'ǻ'     => 'a', 'Ǽ' => 'ae', 'ǽ' => 'ae', 'Ǿ' => 'o', 'ǿ' => 'o', 'ə' => 'e',
                'Ё'     => 'jo', 'Є' => 'e', 'І' => 'i', 'Ї' => 'i', 'А' => 'a', 'Б' => 'b',
                'В'     => 'v', 'Г' => 'g', 'Д' => 'd', 'Е' => 'e', 'Ж' => 'zh', 'З' => 'z',
                'И'     => 'i', 'Й' => 'j', 'К' => 'k', 'Л' => 'l', 'М' => 'm', 'Н' => 'n',
                'О'     => 'o', 'П' => 'p', 'Р' => 'r', 'С' => 's', 'Т' => 't', 'У' => 'u',
                'Ф'     => 'f', 'Х' => 'h', 'Ц' => 'c', 'Ч' => 'ch', 'Ш' => 'sh', 'Щ' => 'sch',
                'Ъ'     => '-', 'Ы' => 'y', 'Ь' => '-', 'Э' => 'je', 'Ю' => 'ju', 'Я' => 'ja',
                'а'     => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e',
                'ж'     => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l',
                'м'     => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's',
                'т'     => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',
                'ш'     => 'sh', 'щ' => 'sch', 'ъ' => '-', 'ы' => 'y', 'ь' => '-', 'э' => 'je',
                'ю'     => 'ju', 'я' => 'ja', 'ё' => 'jo', 'є' => 'e', 'і' => 'i', 'ї' => 'i',
                'Ґ'     => 'g', 'ґ' => 'g', 'א' => 'a', 'ב' => 'b', 'ג' => 'g', 'ד' => 'd',
                'ה'     => 'h', 'ו' => 'v', 'ז' => 'z', 'ח' => 'h', 'ט' => 't', 'י' => 'i',
                'ך'     => 'k', 'כ' => 'k', 'ל' => 'l', 'ם' => 'm', 'מ' => 'm', 'ן' => 'n',
                'נ'     => 'n', 'ס' => 's', 'ע' => 'e', 'ף' => 'p', 'פ' => 'p', 'ץ' => 'C',
                'צ'     => 'c', 'ק' => 'q', 'ר' => 'r', 'ש' => 'w', 'ת' => 't', '™' => 'tm',
                'ء'     => 'a', 'ا' => 'a', 'آ' => 'a', 'ب' => 'b', 'پ' => 'p', 'ت' => 't',
                'ث'     => 's', 'ج' => 'j', 'چ' => 'ch', 'ح' => 'h', 'خ' => 'kh', 'د' => 'd',
                'ر'     => 'r', 'ز' => 'z', 'ژ' => 'zh', 'س' => 's', 'ص' => 's', 'ض' => 'z',
                'ط'     => 't', 'ظ' => 'z', 'غ' => 'gh', 'ف' => 'f', 'ق' => 'q', 'ک' => 'k',
                'گ'     => 'g', 'ل' => 'l', 'م' => 'm', 'ن' => 'n', 'و' => 'w', 'ه' => 'h', 'ی' => 'y ',
            ];
            return strtr($value, $table);
        }
        public static function random_string($length = 6, $letters_only = false){
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            if ($letters_only) {
                $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            }
            $characters_length = strlen($characters);
            $random_string = '';
            for ($i = 0; $i < $length; $i++) {
                $random_string .= $characters[random_int(0, $characters_length - 1)];
            }
            return $random_string;
        }
        public static function is_num($value, $decimal = false, $negative = false){
            if ($negative === true) {
                return is_numeric($value);
            }
            $float = $decimal ? '(.{0,1})[0-9]*' : '';
            return !is_array($value) && preg_match("/^[0-9]+" . $float . "$/", $value);
        }
        public static function preg_check($expression, $value){
            return !is_array($value) and preg_match($expression, $value);
        }
        public static function verify_img($file){
            $txt = file_get_contents($file);
            $patterns = [
                '#\<\?php#i',
                '#&(quot|lt|gt|nbsp);#i',
                '#&\#x([0-9a-f]+);#i',
                '#&\#([0-9]+);#i',
                "#([a-z]*)=([\`\'\"]*)script:#iU",
                "#([a-z]*)=([\`\'\"]*)javascript:#iU",
                "#([a-z]*)=([\'\"]*)vbscript:#iU",
                "#(<[^>]+)style=([\`\'\"]*).*expression\([^>]*>#iU",
                "#(<[^>]+)style=([\`\'\"]*).*behaviour\([^>]*>#iU",
                "#</*(applet|link|style|script|iframe|frame|frameset)[^>]*>#i"
            ];
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $txt)) {
                    return false;
                }
            }
            return true;
        }
        public static function uncompressed_ipv6($ip, $count = 7){return '';}//30 from ip_handling_include.php
        public static function format_code($code){
            $code = htmlentities($code, ENT_QUOTES, 'UTF-8', FALSE);
            $code = str_replace(
                ["  ", "  ", "\t", "[", "]"],
                ["&nbsp; ", " &nbsp;", "&nbsp; &nbsp;", "&#91;", "&#93;"],
                $code
            );
            return preg_replace("/^ {1}/m", "&nbsp;", $code);
        }
        /**
         * @param $value
         * @param int $decimals
         * @param string $dec_point
         * @param string $thousand_sep
         * @param bool $round
         * @param string|bool $acronym
         * @return string
         */
        public static function format_num($value, $decimals = 0, $dec_point = ".", $thousand_sep = ",", $round = true, $acronym = true){
            /** @noinspection PackedHashtableOptimizationInspection *///todo
            $array = [
                13 => $acronym ? "t" : "trillion",
                10 => $acronym ? "b" : "billion",
                7  => $acronym ? "m" : "million",
                4  => $acronym ? "k" : "thousand",
            ];
            if (is_numeric($value)) {
                if ($round === true) {
                    foreach ($array as $length => $rounding) {
                        if (strlen($value) >= $length) {
                            $power = 10 ** ($length - 1);
                            if ($value > $power && $length > 4 && $decimals === null) {
                                $decimals = 2;
                            }
                            return number_format(($value / $power), $decimals, $dec_point, $thousand_sep).$rounding;
                        }
                    }
                }
                return number_format($value, $decimals, $dec_point, $thousand_sep);
            }
            return $value;
        }
        public static function format_float($value){
            return (float)preg_replace('/[^\d.]/', '', $value);
        }
        public static function highlight_words($words, $subject){
            for ($i = 0, $l = count($words); $i < $l; $i++) {
                $word[$i] = str_replace([
                    "\\","+","*","?","[","^","]","$","(",")","{","}","=","!","<",">","|",":","#","-","_"
                ], "", $words[$i]);
                if (!empty($words[$i])) {
                    $subject = preg_replace("#($words[$i])(?![^<]*>)#i",
                        "<span style='background-color:yellow;color:#333;font-weight:bold;padding-left:2px;padding-right:2px;'>\${1}</span>",
                        $subject);
                }
            }
            return $subject;
        }
        public static function rowstart_count($total, $count, $range = 3){
            if ($total > $count) {
                $cur_page = ceil(($total + 1) / $count);
                $pg_cnt = ceil($total / $count);
                if ($pg_cnt <= 1) {
                    return 0;
                }
                $row = min($cur_page + $range, $pg_cnt);
                return ($row - 1) * $count;
            }
            return 0;
        }//
        public static function format_date($format, $time){
            $format = str_replace(
                ['%a', '%A', '%d', '%e', '%u', '%w', '%W', '%b', '%h', '%B', '%m', '%y', '%Y', '%D', '%F', '%x', '%n', '%t', '%H', '%k', '%I', '%l', '%M', '%p', '%P', '%r', '%R', '%S', '%T', '%X', '%z', '%Z', '%c', '%s', '%%'],
                ['D', 'l', 'd', 'j', 'N', 'w', 'W', 'M', 'M', 'F', 'm', 'y', 'Y', 'm/d/y', 'Y-m-d', 'm/d/y', "\n", "\t", 'H', 'G', 'h', 'g', 'i', 'A', 'a', 'h:i:s A', 'H:i', 's', 'H:i:s', 'H:i:s', 'O', 'T', 'D M j H:i:s Y', 'U', '%'],
                $format
            );
            $date = null;
            if (!empty($date)) {
                $date = \DateTimeImmutable::createFromFormat('U', $time);
            }
            return $date->format($format);
        }//
        public static function write_file($file, $data, $flags = null){
            if ($flags === null) {
                $bytes = file_put_contents($file, $data);
            } else {
                $bytes = file_put_contents($file, $data, $flags);
            }
            //if (function_exists('opcache_invalidate')) {
            opcache_invalidate($file, true);
            //}
            return $bytes;
        }//todo
        public static function recursive_remove_dir($dir){
            if (is_dir($dir)) {
                $objects = scandir($dir);
                foreach ($objects as $object) {
                    if ($object !== '.' && $object !== '..') {
                        if (filetype($dir.'/'.$object) === 'dir') {
                            self::recursive_remove_dir($dir.'/'.$object);
                        } else {
                            unlink($dir.'/'.$object);
                        }
                    }
                }
                reset($objects);
                rmdir($dir);
            }
        }
        public static function in_array_r($needle, $haystack, $strict = false){
            foreach ($haystack as $item) {
                if (($strict ? $item === $needle : $item === $needle) || (is_array($item) && self::in_array_r($needle, $item, $strict))) {
                    return true;
                }
            }
            return false;
        }
        //todo move elsewhere from here
        public static function tp_stop(...$string){return '';}//
        public static function tp_safe(...$string){return '';}//
        public static function set_error(...$string){return '';}//
        public static function is_array(...$string){return '';}//
        public static function get_parent(...$string){return '';}//
        public static function get_parent_array(...$string){return '';}//
    }
}else {die;}