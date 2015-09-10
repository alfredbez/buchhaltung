<?php

include_once '../../config.php';
include_once DIR_PHP_SKRIPTS.'controller.php';

$id = mysql_real_escape_string($_POST['id']);    // Rechnungsnummer
$level = mysql_real_escape_string($_POST['level']);
$error = false;
$errMes;

/* PDF generieren	*/
$pdf = new zahlungserinnerung_print($id, $level);
$pdf->Output(ROOT_DIR.'export/rechnung/print/z'.$id.'.pdf');

if (!file_exists(ROOT_DIR.'export/rechnung/print/z'.$id.'.pdf')) {
    $error = true;
    $errMes = 'Zahlungserinnerung konnte nicht erstellt werden!';
    $log->error($errMes);
}

if (!$error) {
    $result = array(
        'status' => 'success',
        'extra' => '',
        );
} else {
    $result = array(
        'status' => 'error',
        'extra' => $errMes,
        );
}
echo json_encode($result);
