<?php
 /* Globale Variable die alle Infos enthält */
$db_info = array();

/* Informationen zu Einheiten auslesen */
$db->query('select * from einheiten');
while($row = $db->fetchRow()){
	$res[] = $row;
}
/* Informationen zu Einheiten in Globalem Array speichern */
$db_info['einheiten'] = $res;

/* Einstellungen auslesen */
$db->query('select * from einstellungen');
while($row = $db->fetchRow()){
	$res[$row['name']] = $row['wert'];
}
/* Informationen zu Einheiten in Globalem Array speichern */
$db_info['einstellungen'] = $res;


$db->query("SELECT * FROM textvorlagen");
$db_info['textvorlagen'] = [];
while($row = $db->fetchRow()){
  $db_info['textvorlagen'][] = $row;
}

/* Informationen an Smarty weitergeben */
$smarty->assign('db_info',$db_info);