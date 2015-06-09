<?php

include_once('../../config.php');
include_once(DIR_PHP_SKRIPTS . 'controller.php');

$id = mysql_real_escape_string($_POST['id']);	// Rechnungsnummer
$error = false;
$errMes;

/* PDF (Druckversion) generieren	*/
$pdf = new rechnung_print($id);
$pdf->Output(ROOT_DIR . 'export/rechnung/print/' .  $id . '.pdf');

if(!file_exists(ROOT_DIR . 'export/rechnung/print/' .  $id . '.pdf')){
	$error = true;
	$errMes = 'Rechnung konnte nicht erstellt werden!';
}

if(!$error){
	$result = array(
		'status' 	=>	'success',
		'extra'		=>	''
		);
}
else{
	$result = array(
		'status' 	=> 'error',
		'extra'		=>	$errMes
		);
}
echo json_encode($result);
?>