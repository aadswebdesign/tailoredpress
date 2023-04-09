<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 31-3-2023
 * Time: 11:34
 */
namespace TP_Core\Classes;
if(ABSPATH) {
    class CoreHelpers{
        public $charset_helper;
        public $css_helper = [];
        public $console_helper = [];
        public $critical_styles_helper =[];
        public $importmap_helper =[];
        public $js_helper = [];
        public $link_helper = [];
        public $logo_helper;
        public $meta_helper = [];
        public $module_js_helper;
        public $title_helper;
        public function getCharsetHelper(){
            return $this->charset_helper;
        }
        public function setCharsetHelper($charset){
            $meta_block = '';
            if($charset){
                $meta_block .= "<meta charset='$charset'>";
            }
            $this->charset_helper = $meta_block;
        }
        public function getCssHelper(){
            $_helper = '';
            foreach ($this->css_helper as $helper){
                $_helper .= $helper;
            }
            return $_helper;
        }
        public function setCssHelper($href,$link_atts = null){
            $style_block = '';
            $hrefs = [];
            if($href){
                $hrefs[] = $href;
                foreach ($hrefs as $link){
                    $style_block .= "<link href='$link' rel='stylesheet' $link_atts/>\n";
                }
                $this->css_helper[] = $style_block;
            }
        }
        public function getConsoleHelpers(){
            $_helper = '';
            foreach ($this->console_helper as $helper){
                $_helper .= $helper;
            }
            return $_helper;
        }
        public function setConsoleHelper($console_id, ...$strings){
            $script_block = '';
            if(null !== $strings){
                foreach ($strings as $str ){
                    ob_start();
                    ?>
                    <script id="<?php echo $console_id ?>">
                        (()=>{
                            const string ="<?php echo $str ?>";
                            const script_id = "<?php echo $console_id ?>";
                            return console.log(`${script_id}: `,string);
                        })();
                    </script>
                    <?php
                    $script_block .= ob_get_clean();
                }
            }
            $this->console_helper[] = $script_block;
        }
        public function getCriticalStylesHelper(){
            $_helper = '<!-- critical styles here! --><style>';
            foreach ($this->critical_styles_helper as $helper){
                $_helper .= $helper;
            }
            $_helper .= '</style>';
            return $_helper;
        }
        public function setCriticalStylesHelper($style_key){
            $style_block = '';
            $keys = [];
            if($style_key){
                $keys[] = $style_key;
                //$style_block .= '';
                foreach ($keys as $key){
                    $style_block .= $key;

                }
                $style_block .= "\n";
            }
            $this->critical_styles_helper[] = $style_block;
        }
        public function getImportmapHelper(){
            $script_block = '';
            $_import_maps = null;
            if($this->importmap_helper){
                $_import_maps = $this->importmap_helper;
                ob_start();
                ?>
                <script type="importmap">{
                    "imports": <?php
                    foreach ($_import_maps as $map){
                        $json_map = json_encode($map);
                        echo stripslashes($json_map);
                    }
                    ?>
                }</script>
                <?php
                $script_block .= ob_get_clean();
            }
            $script_block .= '';
            return $script_block;
        }
        public function setImportmapHelper(array $importmap_helper){
            $this->importmap_helper = $importmap_helper;
        }
        public function getJsHelper(){
            $_helper = '';
            foreach ($this->js_helper as $helper){
                $_helper .= $helper;
            }
            return $_helper;
        }
        public function setJsHelper($js_src,$js_atts = null){
            $script_block = '';
            $js_sources = [];
            if($js_src){
                $js_sources[] = $js_src;
                foreach($js_sources as $src){
                    $script_block .= "<script src='$src' $js_atts></script>\n";
                }
            }
            $this->js_helper[] = $script_block;
        }
        public function getLinkHelper(){
            $_helper = '';
            foreach ($this->link_helper as $helper){
                $_helper .= $helper;
            }
            return $_helper;
        }
        public function setLinkHelper($href,$rel,$link_atts = null){
            $style_block = '';
            $hrefs = [];
            if($href && $rel) {
                $hrefs[] = $href;
                foreach ($hrefs as $link) {
                    $style_block .= "<link href='$link' rel='$rel' $link_atts/>\n";
                }
                $this->link_helper[] = $style_block;
            }
        }
        public function getLogoHelper(){
            return $this->logo_helper;
        }
        public function setLogoHelper($logo_link){
            $style_block = '';
            if($logo_link){
                $style_block .= "<link rel='icon' href='$logo_link' />";
            }
            $this->logo_helper = $style_block;
        }
        public function getMetaHelper(){
            $_helper = '';
            foreach ($this->meta_helper as $helper){
                $_helper .= $helper;
            }
            return $_helper;
        }
        public function setMetaHelper($meta_id,$name = null,$http_equiv = null,$content,$meta_atts = null){
            $meta_block = '';
            $meta_ids = [];
            if($meta_id){
                $meta_ids[] = $meta_id;
                foreach ($meta_ids as $id){
                    if(null !== $name){
                        $meta_block .= "<meta id='$id' name='$name' content='$content' $meta_atts>";
                    }else if(null !== $http_equiv){
                        $meta_block .= "<meta id='$id' http-equiv='" . $http_equiv . "' content='$content' $meta_atts>";
                    }
                }
                $this->meta_helper[] = $meta_block;
            }
        }
        public function getModuleJsHelper(){
            return $this->module_js_helper;
        }
        public function setModuleJsHelper($js_src){
            $script_block = '';
            if($js_src){
                $script_block .= "<script type='module' src='$js_src'></script>";
            }
            $this->module_js_helper = $script_block;
        }
        public function getTitleHelper(){
            return $this->title_helper;
        }
        public function setTitleHelper($title){
            $title_block = '';
            if($title){
                $title_block .= "<title>$title</title>";
            }
            $this->title_helper = $title_block;
        }
    }
}else {die;}