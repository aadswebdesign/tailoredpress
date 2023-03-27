<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 26-3-2023
 * Time: 05:06
 */
namespace TP_Core\Classes;
use TP_Core\Libs\Protector\Validation;
use TP_Core\Traits\core_methods;
use TP_Core\Traits\protect_methods;
//todo use TP_Core\Traits\translate_methods;
//todo use TP_Core\Traits\notify_methods;
if(ABSPATH) {
    class protectHandler{
        use core_methods,protect_methods;//,notify_methods,translate_methods;
        public static $input_errors = [];
        private static $debug = false;
        private static $defender_instance;
        private static $input_name = '';
        private static $input_error_text = [];
        private static $page_hash = '';
        public $ref = [];
        // Declared by Form Sanitizer
        public $field = [];
        public $field_name = '';
        public $field_value = '';
        public $field_default = '';
        public $field_config = [
            'type' => '','value' => '','name' => '','safe_mode' => '',
            'path'  => '','thumbnail_1' => '','thumbnail_2' => '',
        ];
        public function __construct(){
            //todo is temporary
            defined('LANGUAGE') ? null : define('LANGUAGE','');
            defined('USER_IP') ? null : define('USER_IP','');
            //defined('') ? null : define('');
            //defined('') ? null : define('');
        }
        public static function getInstance() {
            if (null === self::$defender_instance) {
                self::$defender_instance = new static();
            }
            return self::$defender_instance;
        }//57
        public static function serialize(array $array = []) {
            $return_default = '';
            if (is_array($array)) {
                return base64_encode(serialize($array));
            }
            return $return_default;
        }//79
        public static function encode($value) {
            return base64_encode(json_encode($value));
        }//86
        public static function decode($value) {
            return json_decode(base64_decode($value), true);
        }//95
        public static function unserialize($string) {
            $return_default = [];
            if (!empty($string)) {
                //$array = unserialize(base64_decode($string));
                /** @noinspection PhpMethodParametersCountMismatchInspection */
                $array = unserialize(self::decode($string),$return_default);
                if (!empty($array)) { return $array;}
            }
            return $return_default;
        }//106
        public static function add_field_session(array $array) {
            $_SESSION['form_fields'][self::pageHash()][$array['input_name']] = $array;
        }//122
        public static function pageHash() {
            if (!defined('SECRET_KEY')) {
                $chars = ['abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ', '123456789'];
                $count = [(strlen($chars[0]) - 1), (strlen($chars[1]) - 1)];
                $key = '';
                for ($i = 0; $i < 32; $i++) {
                    $type = random_int(0, 1);
                    $key .= substr($chars[$type], random_int(0, $count[$type]), 1);
                }
                define('SECRET_KEY', $key);
            }
            if (empty(self::$page_hash)) {  self::$page_hash = md5(SECRET_KEY);}
            return self::$page_hash;
        }//131
        public static function unset_field_session() {
            //session_remove('form_fields');
        }//151 //todo
        public static function sanitize_array($array) {
            foreach ($array as $name => $value) {
                $array[self::strip_input($name)] = trim(self::strip_input($value));
            }
            return (array)$array;
        }//160
        public static function set_sessionUserID() {
            $userdata = self::tp_get_userdata();
            return !empty($userdata['user_id']) && !isset($_POST['login']) ? (int)self::tp_get_userdata('user_id') : str_replace('.', '-', USER_IP);
        }//175
        public static function getInputErrors() {
            return self::$input_errors;
        }//185
        public static function setErrorText($input_name, $text) {
            self::$input_error_text[$input_name] = $text;
        }//195
        public static function getErrorText($input_name) {
            if (self::inputHasError($input_name)) {
                return self::$input_error_text[$input_name] ?? null;
            }
            return null;
        }//208
        public static function inputHasError($input_name) {
            if (isset(self::$input_errors[$input_name])) {
                return true;
            }
            return false;
        }//221
        public static function get_inputError() {
            return self::$input_errors;
        }//232
        public static function get_encrypt_key($private_key) {
            if(false !== $private_key){
                /** @noinspection CryptographicallySecureRandomnessInspection */
                $encrypt = openssl_random_pseudo_bytes(32, $private_key);
                if (false === $encrypt || false === $encrypt) {
                    throw new \RuntimeException('IV generation failed');
                }
                return $encrypt; // 256 bits
            }
            return false;
        }//243
        public static function encrypt_string($string, $private_key = 'phpfusion') {
            $iv_len = openssl_cipher_iv_length($cipher = 'AES-128-CBC');
            if(false !== $iv_len){
                /** @noinspection CryptographicallySecureRandomnessInspection */
                $iv = openssl_random_pseudo_bytes(16, $iv_len); // 128 bits
                if (false === $iv || false === $iv) {
                    throw new \RuntimeException('IV generation failed');
                }
                $string = self::pkcs7_pad($string, 16);
                $ciphertext_raw = openssl_encrypt($string, $cipher, $private_key, OPENSSL_RAW_DATA, $iv);
                $hmac = hash_hmac('sha256', $ciphertext_raw, $private_key, true);
                return base64_encode($iv.$hmac.$ciphertext_raw);
            }
            return false;
        }//257
        private static function pkcs7_pad($data, $size) {
            $length = $size - strlen($data) % $size;
            return $data.str_repeat(chr($length), $length);
        }//273
        public static function decrypt_string($string, $private_key = 'php_fusion') {
            $c = base64_decode($string);
            $iv_len = openssl_cipher_iv_length($cipher = 'AES-128-CBC');
            $iv = substr($c, 0, $iv_len);
            $hmac = substr($c, $iv_len, $sha2len = 32);
            $ciphertext_raw = substr($c, $iv_len + $sha2len);
            $string = openssl_decrypt($ciphertext_raw, $cipher, $private_key, OPENSSL_RAW_DATA, $iv);
            $string = self::pkcs7_unpad($string);
            $calc_mac = hash_hmac('sha256', $ciphertext_raw, $private_key, true);
            if (!function_exists('hash_equals')) {
                function hash_equals($str1, $str2) {
                    if (strlen($str1) !== strlen($str2)) { return false;}
                    $res = $str1 ^ $str2;
                    $ret = 0;
                    for ($i = strlen($res) - 1; $i >= 0; $i--) {
                        $ret |= ord($res[$i]);
                    }
                    return !$ret;
                }
            }
            if (hash_equals($hmac, $calc_mac)) {//PHP 5.6+ timing attack safe comparison
                return $string;
            }
            return null;
        }//286
        private static function pkcs7_unpad($value) {
            $pad = ord($value[strlen($value) - 1]);
            if ($pad > strlen($value)) { return false;}
            if (strspn($value, chr($pad), strlen($value) - $pad) !== $pad) {
                return false;
            }
            return substr($value, 0, -1 * $pad);
        }//323
        public function get_current_field_session($input_name = '') {
            if ($input_name && isset($_SESSION['form_fields'][self::pageHash()][$input_name])) {
                //return $_SESSION['form_fields'][self::pageHash()][$input_name];
                return self::def_session_get(['form_fields', self::pageHash(), $input_name]);
            }
            if ($input_name) {
                return false;
            }
            //return $_SESSION['form_fields'];
            //return $_SESSION['form_fields'][self::pageHash()];
            return self::def_session_get(['form_fields', self::pageHash()]);
        }//346 todo
        public static function safe() {
            if (!defined('TAILORED_NULL')){ return true;}
            return false;
        }//367
        public function addHoneypot(array $array) {
            $_SESSION['honeypots'][self::pageHash()][$array['honeypot']] = $array;
        }//378
        public function getHoneypot($honeypot = '') {
            if ($honeypot && isset($_SESSION['honeypots'][self::pageHash()][$honeypot])) {
                return $_SESSION['honeypots'][self::pageHash()][$honeypot];
            }
            if ($honeypot) { return 'This form contains no honeypots';}
            return $_SESSION['honeypots'][self::pageHash()];
        }//387
        public function debug($value = false) {
            self::$debug = $value;
        }//402
        public function sanitizer($key, $default = '', $input_name = false, $is_multiLang = false) {
            $value = $this->filterPostArray($key);
            return $this->formSanitizer($value, $default, $input_name, $is_multiLang);
        }//416
        public function filterPostArray($key) {
            $flag = FILTER_FLAG_NONE;
            $input_key = $key;
            if (is_array($key)) {
                // always use key 0 for filter var
                $input_key = $key[0];
                $flag = FILTER_REQUIRE_ARRAY;
            }
            $filtered = self::def_post($input_key, FILTER_DEFAULT, $flag);
            if (is_array($key)) {
                $input_ref = $key;
                unset($input_ref[0]);
                // Get the value of the filtered post value using the $key array as map
                return array_reduce(
                    $input_ref,
                    static function ($value, $key) {
                        return (!empty($value[$key]) ? $value[$key] : '');
                    },
                    $filtered
                );
            }
            return $filtered;
        }//426
        public function formSanitizer($value, $default = '', $input_name = false, $is_multiLang = false) {
            $val = [];
            $page_hash = self::pageHash();
            if ($input_name) {
                if ($is_multiLang) {
                    $language = array_keys(self::tp_get_enabled_languages());
                    foreach ($language as $lang) {
                        $i_name = $input_name.'['.$lang.']';
                        if ($this->field_config = $this->get_current_field_session($input_name)) {
                            $this->field_name = $i_name;
                            $this->field_value = $value[$lang];
                            $this->field_default = $default;
                            $val[$lang] = $this->validate();
                        }
                    }
                    if (!empty($this->field_config['required']) && (!$value[LANGUAGE])) {
                        self::tp_stop();
                        $i_name = $input_name.'['.LANGUAGE.']';
                        self::setInputError($i_name);
                        return $default;
                    }
                    $val_ = [];
                    /** @noinspection SuspiciousLoopInspection */
                    foreach ($val as $lang => $value) {
                        $val_[$lang] = $value;
                    }
                    return serialize($val_);
                }
                if ($this->field_config = $this->get_current_field_session($input_name)) {
                    $this->field_config = $_SESSION['form_fields'][$page_hash][$input_name];
                    $this->field_name = $input_name;
                    $this->field_value = $value;
                    $this->field_default = $default;
                    $callback = $this->field_config['callback_check'] ?? FALSE;
                    $regex = $this->field_config['regex'] ?? FALSE;
                    $secured = $this->validate();
                    if ($secured === false || ($this->field_config['required'] === 1 && $secured === '') ||
                        ($secured !== '' && $regex && !preg_match('@^'.$regex.'$@i', $secured)) || // regex will fail for an imploded array, maybe move this check
                        (is_callable($callback) && !$callback($secured))
                    ) {
                        self::tp_stop();
                        self::setInputError($input_name);
                        if ($secured !== '' && $regex && !preg_match('@^'.$regex.'$@i', $secured)) {
                            self::add_notice('danger', sprintf(self::tp_get_locale('regex_error'), $this->field_config['title']));
                        }
                        if (self::$debug) {
                            self::add_notice('warning', '<strong>'.$input_name.':</strong>'.($this->field_config['safemode'] ? ' is in SAFEMODE and the' : '').' check failed');
                        }
                        return $this->field_value;
                    }
                    if (self::$debug) {
                        self::add_notice('info', $input_name.' = '.(is_array($secured) ? 'array' : $secured));
                    }
                    return $secured;
                }
                return $default;
            }
            if ($value) {
                if (!is_array($value)) {
                    if ((int)$value) {
                        return self::strip_input($value); // numbers
                    }
                    return self::strip_input(trim(preg_replace('/ +/i w\/', ' ', $value)));
                }
                $secured = [];
                foreach ($value as $unsecured) {
                    if ((int)$unsecured) {
                        $secured[] = self::strip_input($unsecured); // numbers
                    } else {
                        $secured[] = self::strip_input(trim(preg_replace('/ +/i w\/', ' ', $unsecured)));//todo ?
                    }
                }
                return implode($this->field_config['delimiter'], $secured);
            }
            return $default;
            //set_error(E_USER_NOTICE, "The form sanitizer could not handle the request! (input: $input_name)", "", "");
        }//465 todo
        public function validate() {
            Validation::inputName($this->field_name);
            Validation::inputDefault($this->field_default);
            Validation::inputValue($this->field_value);
            Validation::inputConfig($this->field_config);
            $locale = self::tp_get_locale(LOCALE.LOCALESET.'defender.php'); //todo
            try {
                if (!empty($this->field_config['type'])) {
                    if (empty($this->field_value) && ($this->field_config['type'] !== 'number')) {
                        return $this->field_default;
                    }
                    return Validation::getValidated();
                }
                self::stop();
                self::add_notice('danger', sprintf($locale['df_406'], self::$input_name));
            } catch (\Exception $e) {
                self::stop();
                self::add_notice('danger', $e->getMessage());
            }
            return null;
        }//582
        public static function stop($notice = ''){
            if (!defined('TAILOR_NULL')) {
                define('TAILOR_NULL', true);
                if ($notice) {
                    self::add_notice('danger', $notice);
                    self::consoleHelper('danger',$notice);
                    define('STOP_REDIRECT', true);
                }
            }
            return null;
        }//631
        public static function setInputError($input_name) {
            self::$input_errors[$input_name] = true;
        }//647
        public function fileSanitizer($key, $default = '', $input_name = false) {
            $upload = (array)$this->formSanitizer($_FILES[$key], $default, $input_name);
            if (isset($upload['error']) && $upload['error'] === 0) {
                return $upload;
            }
            return [];
        }//658
        //use defend_methods;

    }
}else {die;}