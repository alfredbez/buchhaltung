<?php
if(isset($_POST['titel']))
{
	foreach ($_POST as $key => $value) {
		$$key = $value;
	}

	if($text === '')
	{
		$message = 'Bitte gib einen Text ein!';
		$success = false;
	}

	$sql = "";
	$sql .= "INSERT INTO textvorlagen (titel, text) ";
	$sql .= "VALUES ('$titel', '$text')";

	if(!isset($message))
	{
		$db->query($sql);
		if($db->affected()==1){
			$message="Die Textvorlage wurde erfolgreich hinzugefügt!";
			$success=true;
		}
		else{
			$message="Es ist ein Fehler aufgetreten! " . mysql_error() ."<br />".$db->getLastQuery();
			$success=false;
		}
	}

	$smarty->assign('message', $message);
	$smarty->assign('success', $success);
}