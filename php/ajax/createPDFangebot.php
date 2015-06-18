<?php

include_once '../../config.php';
include_once DIR_PHP_SKRIPTS.'controller.php';

$id = mysql_real_escape_string($_POST['id']);    // Angebotsnummer
$error = false;
$errMes;

/* PDF generieren	*/
$pdf = new angebot($id);
$pdf->Output(ROOT_DIR.'export/angebot/'.$id.'.pdf');

if (!file_exists(ROOT_DIR.'export/angebot/'.$id.'.pdf')) {
    $error = true;
    $errMes = 'Angebot konnte nicht erstellt werden!';
}

/* Endpreis in Datenbank speichern */
$betrag = $pdf->Endpreis();
$sql = "update angebote set betrag=$betrag where angebotsnummer=$id";
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
