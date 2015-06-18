<?php

$id = mysql_real_escape_string($_GET['id']);
/* gesendete Informationen speichern */
if (isset($_POST['send'])) {
    $messageType = '';
    /* Zeiten löschen */
    $db->query("delete from zeiten where projektID=$id");
    if (mysql_error() != '') {
        $message = 'Es ist ein Fehler aufgetreten! '.__LINE__.'<br />'.mysql_error().'<br />'.$sql;
        $messageType = 'error';
    }
    /* Notizen löschen */
    $db->query("delete from notizen where projektID=$id");
    if (mysql_error() != '') {
        $message = 'Es ist ein Fehler aufgetreten! '.__LINE__.'<br />'.mysql_error().'<br />'.$sql;
        $messageType = 'error';
    }
    /* Projekt löschen */
    $db->query("delete from projekte where id=$id");
    if (mysql_error() != '') {
        $message = 'Es ist ein Fehler aufgetreten! '.__LINE__.'<br />'.mysql_error().'<br />'.$sql;
        $messageType = 'error';
    }
    if ($messageType !== 'error') {
        $message = 'Das Projekt wurde erfolgreich gelöscht.<br /><a href="index.php?site=projekte_uebersicht">Hier</a> geht es zur Projekt-Übersicht.';
        $messageType = 'success';
    }
} else {
    /* Informationen zum Projekt auslesen */
    $sql = "select * from projekte where id=$id";
    $db->query($sql);
    if ($db->count() > 0) {
        /* Formularfelder definieren */
        $fields = array(
            array(
                'name' => 'Dieses Projekt wirklich löschen?',
                'input' => array(
                        'name' => 'sure',
                        'type' => 'hidden',
                        'value' => 'true',
                    ),
                ),
            );

        /* Formulardefinition an Smarty übergeben */
        $smarty->assign('fields', $fields);
    } else {
        $message = 'Es ist ein Fehler aufgetreten! '.mysql_error().'<br />'.$sql;
        $messageType = 'error';
    }
}
if (isset($message)) {
    $smarty->assign('message', $message);
    $smarty->assign('messageType', $messageType);
}
