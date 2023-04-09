<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 7-4-2023
 * Time: 21:14
 */
namespace TP_Core\Traits;
use tp_auto_loaders;
if(ABSPATH) {
    trait utils_methods {


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

        public static function write_file($file, $data, $flags = null) {
            if ($flags === null) {
                $bytes = file_put_contents($file, $data);
            } else {
                $bytes = file_put_contents($file, $data, $flags);
            }
            if (function_exists('opcache_invalidate')) {
                opcache_invalidate($file, true);
            }
            return $bytes;
        }//2395 core_functions_include
        public static function autoload_class($class_name,$namespace,$args = null){
            /** @noinspection OneTimeUseVariablesInspection */
            $tp = new tp_auto_loaders();
            return $tp->load_class($class_name,$namespace,$args);
        }

        public static function consoleHelper($script_id, ...$strings){
            $script_block = '';
            if($strings){
                foreach ($strings as $str ){
                    ob_start();
                    ?>
                    <script id="<?php echo $script_id ?>">
                        (()=>{
                            const string ="<?php echo $str ?>";
                            const script_id = "<?php echo $script_id ?>";
                            return console.log(`${script_id}: `,string);
                        })();
                    </script>
                    <?php
                    $script_block .= ob_get_clean();
                }
            }
            echo $script_block;
        }//added method

    }
}else {die;}

