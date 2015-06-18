<?php

include_once '../../config.php';
include_once DIR_PHP_SKRIPTS.'controller.php';

$id = mysql_real_escape_string($_POST['id']);    // Angebotsnummer
$error = false;
$errMes;

/* PDF (Druckversion) generieren	*/
$pdf = new angebot_print($id);
$pdf->Output(ROOT_DIR.'export/angebot/print/'.$id.'.pdf');

if (!file_exists(ROOT_DIR.'export/angebot/print/'.$id.'.pdf')) {
    $error = true;
    $errMes = 'Angebot konnte nicht erstellt werden!';
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
