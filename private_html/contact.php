<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 27-3-2023
 * Time: 15:00
 */
if ( ! defined( 'ABSPATH' ) ) { define( 'ABSPATH', __DIR__ . '/' );}
$src = 'src/tp_autoload.php';//todo
/** @noinspection PhpIncludeInspection */
require_once(ABSPATH.$src);
$src2 = 'src/tp_auto_loaders.php';
/** @noinspection PhpIncludeInspection */
require_once(ABSPATH.$src2);
if(ABSPATH){
    new tp_autoload();
    $tp = new tp_auto_loaders();
    $tp->load_class('contactIndex','\\TP_Core\\Modules\\Contact\\');
}else{die();}