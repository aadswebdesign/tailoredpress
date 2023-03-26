<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 18-3-2023
 * Time: 19:10
 */
namespace TP_Core\Libs\Database\Traits;
if(ABSPATH) {
    trait db_constants{
        private function pvt_constants(){
            #COUNT
            defined('DB_COUNT') ? null : define('DB_COUNT', 'COUNT');
            #DELETE
            defined('DB_DELETE') ? null : define('DB_DELETE', 'DELETE');
            #FROM
            defined('DB_FROM') ? null : define('DB_FROM', 'FROM');
            #INSERT INTO
            defined('DB_INSERT') ? null : define('DB_INSERT', 'INSERT');
            #SELECT
            defined('DB_SELECT') ? null : define('DB_SELECT', 'SELECT');
            #UPDATE
            defined('DB_UPDATE') ? null : define('DB_UPDATE', 'UPDATE');
            #WHERE
            defined('DB_WHERE') ? null : define('DB_WHERE', 'WHERE');
            #ADD
            defined('TBL_ADD') ? null : define('TBL_ADD', 'ADD');
            #ALTER TABLE
            defined('TBL_ALTER') ? null : define('TBL_ALTER', 'ALTER TABLE ');
            #CREATE TABLE
            defined('TBL_CREATE') ? null : define('TBL_CREATE', 'CREATE TABLE ');
            #DELETE TABLE
            defined('TBL_DELETE') ? null : define('TBL_DELETE', 'DELETE TABLE ');
            #DROP TABLE
            defined('TBL_DROP') ? null : define('TBL_DROP', 'DROP TABLE ');
			#READ TABLE // not sure about this?
            defined('TBL_READ') ? null : define('TBL_READ', 'READ TABLE ');
            #UPDATE TABLE
            defined('TBL_UPDATE') ? null : define('TBL_UPDATE', 'UPDATE TABLE ');
            #
            //defined('') ? null : define('', '');
			#to be moved
            defined('TAILOR_SELF') ? null : define('TAILOR_SELF', '');
            defined('TAILOR_REQUEST') ? null : define('TAILOR_REQUEST', '');
            defined('DB_CUSTOM_PAGES_CONTENT') ? null : define('DB_CUSTOM_PAGES_CONTENT', '');
            defined('DB_CUSTOM_PAGES_GRID') ? null : define('DB_CUSTOM_PAGES_GRID', '');
            defined('LOCALE') ? null : define('LOCALE', '');
            defined('LOCALESET') ? null : define('LOCALESET', '');
            defined('DB_CUSTOM_PAGES') ? null : define('DB_CUSTOM_PAGES', '');
            defined('ADMIN') ? null : define('ADMIN', '');
            defined('iGUEST') ? null : define('iGUEST', '');
            defined('DB_SITE_LINKS') ? null : define('DB_SITE_LINKS', '');
            defined('iAUTH') ? null : define('iAUTH', '');
            defined('WIDGETS') ? null : define('WIDGETS', '');
            defined('iADMIN') ? null : define('iADMIN', '');
            defined('DB_USERS') ? null : define('DB_USERS', '');
            defined('DB_USER_FIELDS') ? null : define('DB_USER_FIELDS', '');
            defined('DB_USER_FIELD_CATS') ? null : define('DB_USER_FIELD_CATS', '');
			defined('DB_PREFIX') ? null : define('DB_PREFIX', '');
			defined('DB_PERMALINK_METHOD') ? null : define('DB_PERMALINK_METHOD', '');
            defined('DB_PERMALINK_REWRITE') ? null : define('DB_PERMALINK_REWRITE', '');
            defined('PERMALINK_CURRENT_PATH') ? null : define('PERMALINK_CURRENT_PATH', '');
            defined('ROOT') ? null : define('ROOT', '');
            defined('TAILORS') ? null : define('TAILORS', '');
            defined('TAILOR_ROOT') ? null : define('TAILOR_ROOT', '');
            defined('iSUPERADMIN') ? null : define('iSUPERADMIN', '');
            defined('DB_PERMALINK_ALIAS') ? null : define('DB_PERMALINK_ALIAS', '');
            defined('INCLUDES') ? null : define('INCLUDES', '');
            defined('USER_LEVEL_ADMIN') ? null : define('USER_LEVEL_ADMIN', '');
            defined('DB_HANDLERS') ? null : define('DB_HANDLERS', '');
            //defined('') ? null : define('', '');
            //defined('') ? null : define('', '');
            //defined('') ? null : define('', '');
            //defined('') ? null : define('', '');
            //defined('') ? null : define('', '');
            //defined('') ? null : define('', '');
            //defined('') ? null : define('', '');
            //defined('') ? null : define('', '');
            //defined('') ? null : define('', '');
            //defined('') ? null : define('', '');
            //defined('') ? null : define('', '');


        }

    }
}else {die;}