<?php

include_once '../../config.php';
include_once DIR_PHP_SKRIPTS.'controller.php';

$rechnungID = (int) $_GET['id'];
$error = false;
$date = mysql_real_escape_string($_GET['date']);

$sql = "UPDATE rechnungen SET bezahlt_am='$date' WHERE rechnungsnummer=$rechnungID";
$db->query($sql);
if ($db->count() == 0) {
    /* Datensatz konnte nicht gespeichert werden */
    $errMes = 'Datensatz konnte nicht gespeichert werden';
    $errMes .= 'ERROR at line <b>'.__LINE__.'</b>:<br />'.mysql_error().'<br /><b>SQL</b>:<br />'.$sql;
    $fehler = true;
}
if (!$error) {
    $result = array(
        'status' => 'success',
        'extra' => $date,
        );
} else {
    // TODO: Log error to logfile (see classes/mysql@construct)
    $result = array(
        'status' => 'error',
        'extra' => $errMes,
        );
}
echo json_encode($result);
