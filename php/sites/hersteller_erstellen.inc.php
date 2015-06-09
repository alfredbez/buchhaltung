<?php
/* gesendete Formulardaten verarbeiten */
if(isset($_POST['send'])){
	foreach($_POST as $k=>$v){
		$$k = ($v === '') ? 0 : mysql_real_escape_string($v);
	}
	$sql="INSERT into hersteller(
		id,
		name,
		kundennummer,
		telefon,
		fax,
		passwort
	) VALUES(
		'$id',
		'$name',
		'$kundennummer',
		 $telefon,
		 $fax,
		'$passwort'
	)";
	$db->query($sql);
	if($db->affected()==1){
		$message="Der Hersteller wurde erfolgreich hinzugefügt!";
		$success=true;
	}
	else{
		$success=false;
	}
	if($success === false){
		$message="Es ist ein Fehler aufgetreten! " . mysql_error() ."<br />" . $sql;
	}

	$smarty->assign('message',$message);
	$smarty->assign('success',$success);
}
?>