<?php

/* Formularfelder definieren */
$fields = array(
    array(
        'name' => 'Vorname',
        'input' => array(
                'name' => 'vorname',
            ),
        ),
    array(
        'name' => 'Nachname',
        'input' => array(
                'name' => 'nachname',
            ),
        ),
    array(
        'name' => 'Telefon',
        'input' => array(
                'name' => 'tel',
            ),
        ),
    array(
        'name' => 'Handy',
        'input' => array(
                'name' => 'handy',
            ),
        ),
    array(
        'name' => 'E-Mail',
        'input' => array(
                'name' => 'email',
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
    $sql = "INSERT into mitarbeiter(
		vorname,
		nachname,
		tel,
		handy,
		email,
		hinweis
	) VALUES(
		'$vorname',
		'$nachname',
		'$tel',
		'$handy',
		'$email',
		'$hinweis'
	)";
    $db->query($sql);
    if ($db->affected() == 1) {
        $message = 'Dieser Mitarbeiter wurde erfolgreich erstellt!';
        $messageType = 'success';
    } else {
        $message = 'Es ist ein Fehler aufgetreten! '.mysql_error().'<br />'.$sql;
        $messageType = 'error';
    }

    $smarty->assign('messageType', $messageType);
    $smarty->assign('message', $message);
}
