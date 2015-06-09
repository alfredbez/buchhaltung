<?php
error_reporting(0);

/*	Angebotsinformationen auslesen	*/
$db->query("select * from angebote where angebotsnummer=" . $_GET['id']);
$angebotData = $db->fetchRow();

/*	Positionen (=Artikel) auslesen	*/
$db->query("select * from positionen where angebotID=" . $_GET['id']);
while($row = $db->fetchRow()){
	$positionData[] = $row;
}

$data = array($angebotData,$positionData);

$smarty->assign('data',$data);
$smarty->assign('edit',true);
?>