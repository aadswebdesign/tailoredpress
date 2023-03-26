<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 20-3-2023
 * Time: 13:19
 */
namespace TP_Core\Libs\Database\Exception;
if(ABSPATH) {
    class SelectionException extends \RuntimeException{}
}else {die;}