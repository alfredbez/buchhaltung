<?php

function fillWithString(&$arr){
	/* Eine '0' in leere Felder eintragen */
	foreach($arr as $key=>$value){
		if($value == ''){
			$arr[$key] = '0';
		}
	}
}

$id = mysql_real_escape_string( $_GET['id'] );

/* Allgemeine Mitarbeiterinformationen auslesen */
$sql = "select 
		id,
		vorname,
		nachname,
		tel,
		email,
		hinweis,
		handy
	from 
		mitarbeiter";
$db->query( $sql );
if($db->count()>0){
	$res = $db->fetchRow();
	/* Eine '0' in leere Felder eintragen */
	fillWithString($res);
}
else{
	$error = "<b>Fehler!</b><br />SQL: $sql<br />MySQL-Error: " . mysql_error();
	$smarty->assign("error",$error);
}


/* Zeiten zu diesem Mitarbeiter auslesen */
$sql = "select 
	zeiten.id,
	zeiten.art,
	zeiten.stunden,
	zeiten.timestamp,
	projekte.name projektname
from 
	zeiten,
	projekte
where
	zeiten.projektID = projekte.id and
	zeiten.mitarbeiterID=$id";
$db->query( $sql );
if($db->count()>0){
	while($row = $db->fetchRow()){
		$res['zeiten'][] = $row;
	}
	/* Eine '0' in leere Felder eintragen */
	fillWithString($res['zeiten']);
}
else if(mysql_error() !== ''){
	$error .= "<b>Fehler!</b><br />SQL: $sql<br />MySQL-Error: " . mysql_error();
	$smarty->assign("error",$error);
}

/* Notizen zu diesem Mitarbeiter auslesen */
$sql = "select
	notizen.id,
	notizen.notiz,
	notizen.timestamp,
	projekte.name projektname
from 
	notizen,
	projekte
where
	notizen.mitarbeiterID = $id and
	notizen.projektID = projekte.id";
$db->query( $sql );
if($db->count()>0){
	while($row = $db->fetchRow()){
		$res['notizen'][] = $row;
	}
	/* Eine '0' in leere Felder eintragen */
	fillWithString($res['notizen']);
}
else if(mysql_error() !== ''){
	$error .= "<b>Fehler!</b><br />SQL: $sql<br />MySQL-Error: " . mysql_error();
	$smarty->assign("error",$error);
}

/* Daten an Smarty übergeben */
$smarty->assign("res",$res);
?>