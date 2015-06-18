<?php

include_once '../../config.php';
include_once DIR_PHP_SKRIPTS.'controller.php';

$id = mysql_real_escape_string($_POST['id']);    // Rechnungsnummer
$error = false;
$errMes;

/* PDF generieren	*/
$pdf = new rechnung($id);
$pdf->Output(ROOT_DIR.'export/rechnung/'.$id.'.pdf');

if (!file_exists(ROOT_DIR.'export/rechnung/'.$id.'.pdf')) {
    $error = true;
    $errMes = 'Rechnung konnte nicht erstellt werden!';
}

/* Endpreis in Datenbank speichern */
$betrag = $pdf->Endpreis();
$sql = "update rechnungen set betrag=$betrag where rechnungsnummer=$id";
$db->query($sql);
if (mysql_error() != '') {
    $error = true;
    $errMes = $sql.'<br />'.mysql_error();
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
