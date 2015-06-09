<?php
define('ROOT_DIR',dirname(__FILE__) . '/');

if( $_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '::1'){
	define('WEB_ROOT','/github/igor/');
}
else{
	define('WEB_ROOT','/neu/');
}


define('DIR_LIBS' , ROOT_DIR . 'libs/');
define('DIR_PHP_SKRIPTS' , ROOT_DIR . 'php/');
define('DIR_SITES' , DIR_PHP_SKRIPTS . 'sites/');
define('DIR_CLASSES' , DIR_PHP_SKRIPTS . 'classes/');
define("FPDF_FONTPATH","font/");

$errorsite = 'error';

include(DIR_PHP_SKRIPTS . 'mysql_config.php');

include_once(DIR_PHP_SKRIPTS . 'functions.inc.php');
include_once(DIR_LIBS . 'smarty/Smarty.class.php');
include_once(DIR_LIBS . 'fpdf/fpdf.php');
$smarty = new Smarty;

header("Content-Type: text/html; charset=utf-8");

?>