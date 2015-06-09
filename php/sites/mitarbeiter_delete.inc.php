<?php
$id = mysql_real_escape_string($_GET['id']);
if(isset($_POST['send'])){
	$messageType = '';
	/* Zeiten löschen */
	$db->query("update zeiten set mitarbeiterID=NULL where mitarbeiterID=$id");
	if(mysql_error() != ''){
		$message="Es ist ein Fehler aufgetreten! " . __LINE__ . '<br />' . mysql_error() ."<br />".$sql;
		$messageType='error';
	}
	/* Notizen löschen */
	$db->query("update notizen set mitarbeiterID=NULL where mitarbeiterID=$id");
	if(mysql_error() != ''){
		$message="Es ist ein Fehler aufgetreten! " . __LINE__ . '<br />' . mysql_error() ."<br />".$sql;
		$messageType='error';
	}
	/* Mitarbeiter löschen */
	$db->query("delete from mitarbeiter where id=$id");
	if(mysql_error() != ''){
		$message="Es ist ein Fehler aufgetreten! " . __LINE__ . '<br />' . mysql_error() ."<br />".$sql;
		$messageType='error';
	}
	if($messageType !== 'error'){
		$message="Dieser Mitarbeiter wurde erfolgreich gelöscht.<br /><a href=\"index.php?site=mitarbeiter_uebersicht\">Hier</a> geht es zur Mitarbeiter-Übersicht.";
		$messageType='success';	
	}
}
else{
	/* Informationen zum Mitarbeiter auslesen */
	$sql = "select * from mitarbeiter where id=$id";
	$db->query($sql);
	if($db->count() > 0){
		/* Formularfelder definieren */
		$fields = array(
			array(
				'name' => 'Diesen Mitarbeiter wirklich löschen?',
				'input' => array(
						'name'  => 'sure',
						'type'  => 'hidden',
						'value' => 'true'
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
}
if(isset($message)){
	$smarty->assign('message',$message);
	$smarty->assign('messageType',$messageType);
}

?>