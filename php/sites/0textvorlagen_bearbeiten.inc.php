<?php
/* get values from database */
$id = isset($_GET['id']) ? $_GET['id'] : $_POST['id'];
$id = mysql_real_escape_string($id);
$sql = "SELECT * FROM textvorlagen WHERE id = $id";
$db->query($sql);
$data = $db->fetchRow();
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
	$sql .= "UPDATE textvorlagen SET";
	$changed = false;
	foreach ($data as $key => $value)
	{
		if($value != $$key)
		{
			$sql .= " $key='{$$key}',";
			$data[$key] = $$key;
			$changed = true;
		}
	}
	// letztes Komma entfernen
	$sql = substr($sql, 0, -1);
	$sql .= " WHERE id=$id";

	if(!$changed)
	{
		$message = "Du hast keine neuen Angaben gemacht!";
		$success = false;
	}

	if(!isset($message))
	{
		$db->query($sql);
		if($db->affected()==1){
			$message="Die Textvorlage wurde erfolgreich geändert!";
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
$smarty->assign('data', $data);