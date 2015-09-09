<?php

define('ROOT_DIR', dirname(__FILE__).'/');

$localIps = array('192.168.56.1', '127.0.0.1', '::1');
if (isset($_SERVER['REMOTE_ADDR'])) {
    $env = in_array($_SERVER['REMOTE_ADDR'], $localIps) ? 'local' : 'remote';
} else {
    $env = 'local';
}

if (getenv('env') === 'testing') {
    $env = 'local';
}

if ($env === 'local') {
    define('WEB_ROOT', '/igor/');
} else {
    define('WEB_ROOT', '/neu/');
}

if ($env === 'local') {
    ini_set('display_errors', 'On');
}
error_reporting(E_ALL ^ E_DEPRECATED);

define('DIR_LIBS', ROOT_DIR.'libs/');
define('DIR_PHP_SKRIPTS', ROOT_DIR.'php/');
define('DIR_SITES', DIR_PHP_SKRIPTS.'sites/');
define('DIR_CLASSES', DIR_PHP_SKRIPTS.'classes/');
define('FPDF_FONTPATH', ROOT_DIR.'vendor/itbz/fpdf/src/fpdf/font/');

$errorsite = 'error';

include DIR_PHP_SKRIPTS.'mysql_config.php';

include_once DIR_PHP_SKRIPTS.'functions.inc.php';
require_once 'vendor/autoload.php';
$smarty = new Smarty();

header('Content-Type: text/html; charset=utf-8');
