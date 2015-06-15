<?php
if(isset($_POST['vorname'])){
	foreach($_POST as $k=>$v){
		$$k=mysql_real_escape_string($v);
	}
	$required_field = ['titel', 'vorname', 'nachname'];
	$error = true;
	foreach ($required_field as $field) {
		if($$field !== '')
		{
			$error = false;
		}
	}
	if(!$error){
		$sql="INSERT into kunden(";
		if($kundennummer!=''){
			$sql.="kundennummer,";
		}
		$sql.="
			titel,
			vorname,
			nachname,
			adresse,
			plz,
			ort,
			geschlecht,
			mail,
			fax,
			telefon,
			bemerkung
		) VALUES(";
		if($kundennummer!=''){
			$sql.="'" . $kundennummer . "',";
		}
		$sql.="
			'" . $titel . "',
			'" . $vorname . "',
			'" . $nachname . "',
			'" . $adresse . "',
			'" . $plz . "',
			'" . $ort . "',
			'" . $geschlecht . "',
			'" . $mail . "',
			'" . $fax . "',
			'" . $telefon . "',
			'" . $bemerkung . "'
		)";
		$db->query($sql);
		if($db->affected()==1){
			$message="Der Kunde wurde erfolgreich hinzugefügt!";
			$success=true;
		}
		else{
			// @TODO: use $db->getLastQuery() instead of $db->queries[1]
			$message="Es ist ein Fehler aufgetreten! " . mysql_error() ."<br />".$db->queries[1];
			$success=false;
		}
	}
	else{
		$message="Bitte gib einen Titel, Vorname oder Nachnamen ein!";
		$success=false;
	}
  $smarty->assign('message',$message);
  $smarty->assign('success',$success);
}
