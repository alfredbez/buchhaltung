<?php
$db->query("select * from mitarbeiter");
$keys = array(
	'id',
	'vorname',
	'nachname',
	'tel',
	'handy',
	'email',
	'hinweis'
);
$res=array();
while($row = $db->fetchRow()){
	$temp = NULL;
	foreach($keys as $key){
		$temp[$key] = $row[$key];
	}
	$res[] = $temp;
}
$smarty->assign('res',$res);
$smarty->assign('keys',$keys);
?>