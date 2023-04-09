<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 8-4-2023
 * Time: 21:34
 */
namespace TP_Core\gateway;
use TP_Core\Classes\CoreHelpers;
use TP_Core\Libs\Components\FooterComponent;
use TP_Core\Traits\utils_methods;
if(ABSPATH) {
    class gtw_construct extends gtw_constants {
        use gtw_methods,utils_methods;
        private $__ft_script;
        public $info;
        public function __construct(){
            parent::__construct();
            $ch = new CoreHelpers();
            $this->__ctrl_lock_file();
            $multiplier = "0";
            $reply_method = '';
            $this->info = ['showform' => false,'incorrect_answer' => false];
            if (!isset($_POST['gateway_submit']) && !isset($_POST['register'])) {
                $a = random_int(11, 20);
                $b = random_int(1, 10);
                $method = self::tp_get_settings('gateway_method');
                if ($method === 0) {
                    $antibot = $a + $b;
                    $multiplier = "+";
                    $reply_method .= self::$locale['gateway_062'];
                    $a = self::convertNumberToWord($a);
                    $antibot = self::convertNumberToWord($antibot);
                    $_SESSION["antibot"] = strtolower($antibot);
                } else if ($method === 1) {
                    $antibot = $a - $b;
                    $multiplier = "-";
                    $reply_method .= self::$locale['gateway_063'];
                    $_SESSION["antibot"] = $antibot;
                    $b = self::convertNumberToWord($b);
                }else if ($a > 15) {
                    $antibot = $a + $b;
                    $multiplier = "+";
                    $reply_method .= self::$locale['gateway_062'];
                    $a = self::convertNumberToWord($a);
                    $antibot = self::convertNumberToWord($antibot);
                    $_SESSION["antibot"] = strtolower($antibot);
                } else {
                    $antibot = $a - $b;
                    $multiplier .= "-";
                    $reply_method .= self::$locale['gateway_063'];
                    $_SESSION["antibot"] = $antibot;
                    $b = self::convertNumberToWord($b);
                }
                $a = self::str_rot47($a);
                $b = self::str_rot47($b);
                $honeypot_array = [self::$locale['gateway_053'], self::$locale['gateway_054'], self::$locale['gateway_055'], self::$locale['gateway_056'], self::$locale['gateway_057'], self::$locale['gateway_058'], self::$locale['gateway_059']];
                shuffle($honeypot_array);
                $_SESSION["honeypot"] = $honeypot_array[3];
                ob_start();
                ?>
                <script id='honey_pot'>
                    function decode(x) {
                        let s = "";
                        for (let i = 0; i < x.length; i++) {
                            let j = x.charCodeAt(i);
                            if ((j >= 33) && (j <= 126)) {
                                s += String.fromCharCode(33 + ((j + 14) % 94));
                            } else {
                                s += String.fromCharCode(j);
                            }
                        }
                        return s;
                    }
                    const gateway = document.querySelector('#gateway_question');
                    const locale_60 = "<?php echo self::$locale['gateway_060'] ?>";
                    const locale_61 = "<?php echo self::$locale['gateway_061'] ?>";
                    const a = "<?php echo $a ?>";
                    const b = "<?php echo $b ?>";
                    const reply_method = "<?php echo $reply_method ?>";
                    const multiplier = "<?php echo $multiplier ?>";
                    gateway.innerText = `${locale_60} ${decode(a)} ${multiplier} ${decode(b)} ${locale_61} ${reply_method}`;
                </script>
                <?php
                $this->__ft_script = ob_get_clean();
                $ch->setConsoleHelper('honeypot','todo for now in footer but later moved to module.');
                $this->__ft_script .= $ch->getConsoleHelpers();
            }
            if (isset($_POST['gateway_answer'])) {
                $honeypot = $_SESSION["honeypot"] ?? '';
                $_SESSION["validated"] = "False";
                if (isset($_POST["(string)$honeypot"]) && $_POST["(string)$honeypot"] === "") {
                    $antibot = self::strip_input(strtolower($_POST["gateway_answer"]));
                    if (isset($_SESSION["antibot"])) {
                        if ($_SESSION["antibot"] === $antibot) {
                            $_SESSION["validated"] = "True";
                            self::redirect(__DIR__."register/index.php");
                        } else {
                            $this->info['incorrect_answer'] = TRUE;
                        }
                    }
                }
            }
        }
        private function __display_gateway(...$info){
            $output  = "";
            if ($info['showform'] === true) {
                $output .= "<ul class='relative'>";
                $output .= "<li class='relative'>" . $info['openform'] . "</li>";
                $output .= "<li class='relative'>" . $info['hiddeninput'] . "</li>";
                $output .= "<li class='relative'><h3>" . $info['gateway_question'] . "</h3></li>";
                $output .= "<li class='relative'>" . $info['textinput'] . "</li>";
                $output .= "<li class='relative'>" . $info['button'] . "</li>";
                $output .= "<li class='relative'>" . $info['closeform'] . "</li>";
                $output .= "</ul>";
            }else if (!isset($_SESSION["validated"])){
                $output .= "<div class='todo text-center relative'>";
                $output .= "<h3 class='relative'>" . self::$locale['gateway_068'] . "</h3>";
                $output .= "</div><!-- todo text-center -->";
            }
            if (isset($info['incorrect_answer']) && $info['incorrect_answer'] === true) {
                $output .= "<ul class='relative'><li>";
                $output .= "<dt class='todo'><h3 class='relative'>" . self::$locale['gateway_066'] . "</h3></dt>";
                $output .= "<dd><input class='todo' type='button' value='" . self::$locale['gateway_067'] . "' /></dd>";//todo adding onclick to it
                $output .= "</li></ul>";
            }
            return $output;
        }
        private function __to_string(){
            $html = "__to_string";
            if (!isset($_POST['gateway_submit']) && !isset($_POST['register'])){
                $html .= "<noscript>".self::$locale['gateway_052']."</noscript>";
                $html .= new FooterComponent();
            }
            $html .= $this->__display_gateway($this->info);
            return $html;
        }
        public function __toString(){
            return $this->__to_string();
        }
    }
}else {die;}