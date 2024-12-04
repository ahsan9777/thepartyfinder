<?php
//$sessTime = 24*60*60;
//ini_set('session.gc_maxlifetime', $sessTime);
//ini_set('session.gc_probability', 1);
//ini_set('session.gc_divisor', 1);
//ini_set('display_errors', 1);
//error_reporting(E_ALL);

define( 'DB_NAME', 'thepartyfind' );



/** Database username */

define( 'DB_USER', 'thepartyfindu' );



/** Database password */

define( 'DB_PASSWORD', 'lv%TPF22M))};JM4I' );



/** Database hostname */

define( 'DB_HOST', 'localhost' );

$GLOBALS['conn'] = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
mysqli_set_charset($GLOBALS['conn'], 'utf8');


?>
