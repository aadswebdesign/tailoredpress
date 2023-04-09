<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 26-3-2023
 * Time: 01:49
 */
namespace TP_Core\Traits;
if(ABSPATH) {
    trait core_methods{
        public static $filepath;
        public static function close_table(){return '';}//
        public static function open_table($string){return '';}//

        public static function redirect($location, $delay = false, $script = false, $code = 200){
            if (!defined('STOP_REDIRECT')) {
                if (self::is_num($delay)) {
                    $ref = "<meta http-equiv='refresh' content='$delay; url=".$location."' />";
                    self::add_to_head($ref);
                } else {
                    if ($script === false && !headers_sent()) {
                        self::set_status_header($code);
                        header("Location: ".str_replace("&amp;", "&", $location));
                    } else {
                        echo "<script id='redirect' type='text/javascript'>document.location.href='".str_replace("&amp;", "&", $location)."'</script>\n";
                    }
                    exit;
                }
            }
        }
        public static function tp_get_settings($key = null){
            static $settings = [];
            if (empty($settings) && defined('DB_SETTINGS') && self::db_connection() && self::db_exists('settings')) {
                $result = self::db_query("SELECT * FROM ".DB_SETTINGS);
                while ($data = self::db_array($result)) {
                    $settings[$data['settings_name']] = $data['settings_value'];
                }
            }
            return $key === null ? $settings : ($settings[$key] ?? null);
        }//2134
        /**
         * @param $folder
         * @param array|string $filter
         * @param bool $sort
         * @param string $type
         * @param array|string $ext_filter
         * @return array
         */
        public static function make_filelist($folder, $filter = "", $sort = true, $type = "files", $ext_filter = ""){
            $res = [];
            $default_filters = '.|..|.htaccess|index.php|._DS_STORE|.tmp'; //todo
            if ($filter === false) {
                $filter = $default_filters;
            }
            $filter = explode("|", $filter);
            if ($type === "files" && !empty($ext_filter)) {
                $ext_filter = explode("|", strtolower($ext_filter));
            }
            if (file_exists($folder)) {
                $temp = opendir($folder);
                while ($file = readdir($temp)) {
                    if ($type === "files" && !in_array($file, $filter,true)) {
                        if (!empty($ext_filter)) {
                            if (!is_dir($folder.$file) && !in_array(strtolower(substr(strstr($file, '.'), +1)), $ext_filter,true)) {
                                $res[] = $file;
                            }
                        } else if (is_file($folder.$file)) {
                            $res[] = $file;
                        }
                    } else if($type === "folders" && !in_array($file, $filter, true) && is_dir($folder . $file)) {
                        $res[] = $file;
                    }
                }
                closedir($temp);
                if ($sort) {
                    natsort($res);
                }
            } else {
                $error_log = debug_backtrace()[1];
                $function = ($error_log['class'] ?? '').($error_log['type'] ?? '').($error_log['function'] ?? '');
                $error_log = strtr(self::tp_get_locale('err_103', LOCALE.LOCALESET.'errors.php'), [
                    '{%folder%}'   => $folder,
                    '{%function%}' => (!empty($function) ? '<code class=\'m-r-10\'>'.$function.'</code>' : '')
                ]);
                self::set_error(2, $error_log, debug_backtrace()[1]['file'], debug_backtrace()[1]['line']);
            }
            return $res;
        }//
        public static function parse_byte_size($size, $decimals = 2, $dir = false){
            $locale = self::tp_get_locale();
            $kb = 1024;
            $mb = 1024 * $kb;
            $gb = 1024 * $mb;
            $tb = 1024 * $gb;
            $size = (empty($size)) ? "0" : $size;
            if (($size === 0) && ($dir)) { return "0 ".$locale['global_460']; }
            if ($size < $kb) { return $size.$locale['global_461']; }
            if ($size < $mb) { return round($size / $kb, $decimals).'kB';}
            if ($size < $gb) { return round($size / $mb, $decimals).'MB';}
            if ($size < $tb) { return round($size / $gb, $decimals).'GB';}
            return round($size / $tb, $decimals).'TB';
        }//
        public static function check_rights($rights){return '';}//1262 todo
        public static function show_date($format, $val, $options = []){
            $userdata = self::tp_get_userdata();
            if (isset($options['tz_override'])) {
                $tz_client = $options['tz_override'];
            } else if (!empty($userdata['user_timezone'])) {
                $tz_client = $userdata['user_timezone'];
            } else {
                $tz_client = self::tp_get_settings('timeoffset');
            }
            if (empty($tz_client)) {
                $tz_client = 'Europe/London';
            }
            $offset = 0;
            try {
                $client_dtz = new \DateTimeZone($tz_client);
                $client_dt = new \DateTime('now', $client_dtz);
                $offset = (int)$client_dtz->getOffset($client_dt);
            } catch (\Exception $e) {
                self::set_error(E_CORE_ERROR, $e->getMessage(), $e->getFile(), $e->getLine());
            }
            if (!empty($val)) {
                $offset = (int)$val + $offset;
                if (in_array($format, ['shortdate', 'longdate', 'forumdate', 'newsdate'])) {
                    $format = self::tp_get_settings($format);
                    return self::format_date($format, $offset);
                }
                return self::format_date($format, $offset);
            }
            $format = self::tp_get_settings($format);
            $offset = time() + $offset;
            return self::format_date($format, $offset);
        }//
        public static function clean_request($request_addition = '', $filter_array = [], $keep_filtered = true){
            $tp_query = [];
            if (defined('IN_PERMALINK') && !isset($_GET['aid']) && self::tp_get_settings("site_seo")) {
                //global $filepath;
                self::$filepath;
                $url['path'] = self::$filepath;
                if (!empty($_GET)) {
                    $tp_query = $_GET;
                }
            } else {
                $url = ((array)parse_url(htmlspecialchars_decode($_SERVER['REQUEST_URI']))) + [
                        'path'  => '',
                        'query' => ''
                    ];
                if ($url['query']) {
                    parse_str($url['query'], $tp_query); // this is original.
                }
            }
            if ($keep_filtered) {
                $tp_query = array_intersect_key($tp_query, array_flip($filter_array));
            } else {
                $tp_query = array_diff_key($tp_query, array_flip($filter_array));
            }
            if ($request_addition) {
                $request_addition_array = [];
                if (self::is_array($request_addition)) {
                    $tp_query .= $request_addition;
                } else {
                    parse_str($request_addition, $request_addition_array);
                    $tp_query .= $request_addition_array;
                }
            }
            $prefix = $tp_query ? '?' : '';
            return $url['path'].$prefix.http_build_query($tp_query, 'flags_', '&amp;');
        }
        public static function tp_get_aid_link(){return '';}//2214
        public static function calling_codes(...$string){return '';}//

        //todo moving
        public static function tp_set_cookie(...$string){return '';}//
        public static function de_script(...$string){return '';}//
        public static function censor_words(...$string){return '';}//
        public static function theme_exists(...$string){return '';}//
        public static function set_theme(...$string){return '';}//
        //public static function (...$string){return '';}//
        //public static function (...$string){return '';}//
        //public static function (...$string){return '';}//

    }
}else {die;}