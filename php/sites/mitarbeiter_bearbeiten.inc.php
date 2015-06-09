<?php
$id = mysql_real_escape_string($_GET['id']);
/* gesendete Informationen speichern */
if(isset($_POST['send'])){
	/* alte Informationen auslesen */
	$sql = "select * from mitarbeiter where id=$id";
	$db->query($sql);
	if($db->count() > 0){
		$old = $db->fetchRow();
		/* neue Informationen zum Vergleich vorbereiten */
		$new = array();
		foreach($_POST as $key=>$value){
			$new[$key] = $value;
		}
		/* Informationen vergleichen */
		$changed = array();
		$skip_fields = array(
			'id'
			);
		$numeric = array();
		foreach ($old as $key => $value) {
			if(in_array($key, $skip_fields)){
				continue;
			}
			if($old[$key] !== $new[$key]){
				$v = $new[$key];
				if( !in_array($key, $numeric) ){
					$v = "'$v'";
				}
				$item = "$key=$v";
				$changed[] = $item;
			}
		}
		$changed = implode(",",	$changed);
		/* neue Informationen in DB speichern */
		$sql = "update mitarbeiter set $changed where id=$id";
		$db->query($sql);
		if($db->count() > 0){
			$message="Die Mitarbeiterinformationen wurden erfolgreich geändert.<br /><a href=\"index.php?site=mitarbeiter_display&id=$id\">Mitarbeiter öffnen</a>";
			$messageType='success';	
		}
		else{
			$message="Es ist ein Fehler aufgetreten! " . mysql_error() ."<br />".$sql;
			$messageType='error';
		}
	}
	else{
		$message="Es ist ein Fehler aufgetreten! " . mysql_error() ."<br />".$sql;
		$messageType='error';
	}
}

/* Informationen zum Projekt auslesen */
$sql = "select * from mitarbeiter where id=$id";
$db->query($sql);
if($db->count() > 0){
	$res = $db->fetchRow();
	/* Formularfelder definieren */
	$fields = array(
	array(
		'name'  => 'Vorname',
		'input' => array(
				'name' => 'vorname',
				'value' => $res['vorname']
			)
		),
	array(
		'name'  => 'Nachname',
		'input' => array(
				'name' => 'nachname',
				'value' => $res['nachname']
			)
		),
	array(
		'name'  => 'Telefon',
		'input' => array(
				'name' => 'tel',
				'value' => $res['tel']
			)
		),
	array(
		'name'  => 'Handy',
		'input' => array(
				'name' => 'handy',
				'value' => $res['handy']
			)
		),
	array(
		'name'  => 'E-Mail',
		'input' => array(
				'name' => 'email',
				'value' => $res['email']
			)
		),
	array(
		'name'  => 'Hinweis',
		'textarea' => array(
				'name' => 'hinweis',
				'value' => $res['hinweis']
			)
		),
	);

	/* Formulardefinition an Smarty übergeben */
	$smarty->assign('fields',$fields);
}
else{
	$message="Es ist ein Fehler aufgetreten! " . mysql_error() ."<br />".$sql;
	$messageType='error';
}
if(isset($message)){
	$smarty->assign('message',$message);
	$smarty->assign('messageType',$messageType);
}

?>