<?php

include_once '../../config.php';
include_once DIR_PHP_SKRIPTS.'controller.php';

/*
//	DEBUG
print_r($_POST);
die('');
 */

$id = mysql_real_escape_string($_GET['id']);

// die($id);

/* Datenbankeinträge löschen */
    /* Aus Tabelle angebote */
    $sql = 'delete from angebote where angebotsnummer='.$id;
    $db->query($sql);
    if ($db->count() !== 1) {
        $error = true;
        $errMes = 'ERROR at line <b>'.__LINE__.'</b>:<br />'.mysql_error().'<br /><b>SQL</b>:<br />'.$sql;
    }
    /* Aus Tabelle positionen */
    $sql = 'delete from positionen where angebotID='.$id;
    $db->query($sql);

/* PDF Dateien löschen */
    /* Version mit Hintergrund löschen */
    $file_with_bg = ROOT_DIR.'export/angebot/'.$id.'.pdf';
    if (!unlink($file_with_bg)) {
        $error = true;
        $errMes = 'ERROR at line <b>'.__LINE__.'</b>Datei "'.$file_with_bg.'" konnte nicht gelöscht werden!';
    }
    /* Version ohne Hintergrund löschen */
    $file_without_bg = ROOT_DIR.'export/angebot/print/'.$id.'.pdf';
    if (!unlink($file_without_bg)) {
        $error = true;
        $errMes = 'ERROR at line <b>'.__LINE__.'</b>Datei "'.$file_without_bg.'" konnte nicht gelöscht werden!';
    }

if (!$error) {
    $result = array(
        'status' => 'success',
        'extra' => $id,
        );
} else {
    $result = array(
        'status' => 'error',
        'extra' => $errMes,
        );
}
echo json_encode($result);
