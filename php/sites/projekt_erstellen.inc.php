<?php

/* Formularfelder definieren */
$fields = array(
    array(
        'name' => 'KundeId',
        'class' => 'kundeID',
        'disableAutocomplete' => true,
        'input' => array(
                'name' => 'kundeID',
            ),
        ),
    array(
        'name' => 'Projektname',
        'input' => array(
                'name' => 'name',
            ),
        ),
    array(
        'name' => 'Stunden gesamt',
        'input' => array(
                'name' => 'stundenGesamt',
            ),
        ),
    array(
        'name' => 'Hinweis',
        'textarea' => array(
                'name' => 'hinweis',
            ),
        ),
    );
/* Daten an Smarty übergeben */
$smarty->assign('fields', $fields);

/* empfangenes Formular auswerten */
if (isset($_POST['send'])) {
    foreach ($_POST as $k => $v) {
        $$k = mysql_real_escape_string($v);
    }
    $erstellDatum = date('Y-m-d', time());
    $sql = 'INSERT into projekte(
		kundeID,
		name,
		stundenGesamt,
		erstellDatum,
		hinweis,
		status
	) VALUES(
		'.$kundeID.",
		'".$name."',
		".$stundenGesamt.",
		'".$erstellDatum."',
		'".$hinweis."',
		'in Bearbeitung'
	)";
    $db->query($sql);
    if ($db->affected() == 1) {
        $id = mysql_insert_id();
        $message = "Das Projekt wurde erfolgreich erstellt!<br /><a href=\"index.php?site=projekt_display&id=$id\">Hier</a> geht es zum Projekt";
        $messageType = 'success';
    } else {
        $message = 'Es ist ein Fehler aufgetreten! '.mysql_error().'<br />'.$sql;
        $messageType = 'error';
    }

    $smarty->assign('messageType', $messageType);
    $smarty->assign('message', $message);
}
