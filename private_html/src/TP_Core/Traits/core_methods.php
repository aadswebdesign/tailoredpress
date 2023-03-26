<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 26-3-2023
 * Time: 01:49
 */
namespace TP_Core\Traits;
use tp_auto_loaders;
if(ABSPATH) {
    trait core_methods{
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
        }
    }
}else {die;}