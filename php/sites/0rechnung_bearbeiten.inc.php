<?php
error_reporting(0);

/*	Rechnungsinformationen auslesen	*/
$db->query("select * from rechnungen where rechnungsnummer=" . $_GET['id']);
$rechnungData = $db->fetchRow();

/*	Positionen (=Artikel) auslesen	*/
$db->query("select * from positionen where rechnungID=" . $_GET['id']);
while($row = $db->fetchRow()){
	$positionData[] = $row;
}

$data = array($rechnungData,$positionData);

$smarty->assign('data',$data);
$smarty->assign('edit',true);
?>