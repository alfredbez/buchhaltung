<?php

/* gesendete Formulardaten verarbeiten */
if (isset($_POST['send'])) {
    foreach ($_POST as $k => $v) {
        $$k = ($v === '') ? 0 : mysql_real_escape_string($v);
    }
    /* id ermitteln */
    $sql = "select id from artikel where herstellerID='$herstellerID' order by id desc limit 1";
    $db->query($sql);
    $row = $db->fetchRow();
    /* wenn es der erste Artikel des Herstellers ist, wird die id auf 1 gesetzt, ansonsten wird die vorhandene id um 1 hochgezählt */
    $id = ($row['id'] === null) ? 1 : $row['id'] + 1;
    $sql = "INSERT into artikel(
		herstellerID,
		id,
		artikelnummer,
		name,
		menge,
		einheit,
		einzelpreis,
		gesamtpreis,
		lieferzeit
	) VALUES(
		'$herstellerID',
		$id,
		'$artikelnummer',
		'$name',
		".c2d($menge).",
		'$einheit',
		".c2d($einzelpreis).',
		'.c2d($gesamtpreis).",
		'$lieferzeit'
	)";
    $db->query($sql);
    if ($db->affected() == 1) {
        $message = 'Der Artikel wurde erfolgreich hinzugefügt!';
        $success = true;
    } else {
        $success = false;
    }
    if ($success === false) {
        $message = 'Es ist ein Fehler aufgetreten! '.mysql_error().'<br />'.$sql;
    }

    $smarty->assign('message', $message);
    $smarty->assign('success', $success);
}
