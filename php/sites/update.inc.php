<?php
$error = '';
/* DB-Update-Skript */
$sql[] = "ALTER TABLE rechnungen ADD endbetrag_typ VARCHAR(10) NOT NULL";
$sql[] = "ALTER TABLE angebote ADD endbetrag_typ VARCHAR(10) NOT NULL";
$sql[] = "UPDATE rechnungen SET endbetrag_typ='brutto'";
$sql[] = "UPDATE angebote SET endbetrag_typ='brutto'";
foreach($sql as $s){
	$db->query($s);
	if($db->count() == 0){
		$error .= 'Datensatz wurde nicht erfolgreich gespeichert!<br />' . $s . '<br />';
	}
	if(mysql_error()!=''){
		$error .= mysql_error();
		$error .= '<br />' . $s . '<br />';
	}
	if($error != ''){
		$error .= '<br />';
	}
}
if($error != ''){
	$smarty->assign('message',$error);
	$smarty->assign('messageType','error');
}
else{
	$smarty->assign('message','Updates wurden erfolgreich durchgeführt!');
	$smarty->assign('messageType','success');
}
?>