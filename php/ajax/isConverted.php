<?php

include_once '../../config.php';
include_once DIR_PHP_SKRIPTS.'controller.php';

$id = mysql_real_escape_string($_GET['id']);

$error = false;

$sql = 'select converted from angebote where angebotsnummer='.$id;
$db->query($sql);
$res = $db->fetchRow();
if ($res['converted'] > 0) {
    $rechnungsnummer = $res['converted'];
} else {
    $error = true;
    $errMes = 'not converted';
}
if (mysql_error() !== '') {
    $error = true;
    $errMes = mysql_error();
}

if (!$error) {
    $result = array(
        'status' => 'success',
        'extra' => $rechnungsnummer,
        );
} else {
    $result = array(
        'status' => 'error',
        'extra' => $errMes,
        );
}
echo json_encode($result);
