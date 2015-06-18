<?php

$id = mysql_real_escape_string($_GET['id']);
/* gesendete Informationen speichern */
if (isset($_POST['send'])) {
    /* alte Informationen auslesen */
    $sql = "select * from projekte where id=$id";
    $db->query($sql);
    if ($db->count() > 0) {
        $old = $db->fetchRow();
        /* neue Informationen zum Vergleich vorbereiten */
        $new = array();
        foreach ($_POST as $key => $value) {
            $new[$key] = $value;
        }
        /* Informationen vergleichen */
        $changed = array();
        $skip_fields = array(
            'id',
            'stundenBisher',
            'erstellDatum',
            'fertigDatum',
            );
        $numeric = array(
            'kundeID',
            'stundenGesamt',
            );
        foreach ($old as $key => $value) {
            if (in_array($key, $skip_fields)) {
                continue;
            }
            if ($old[$key] !== $new[$key]) {
                $v = $new[$key];
                if (!in_array($key, $numeric)) {
                    $v = "'$v'";
                }
                $item = "$key=$v";
                $changed[] = $item;
            }
        }
        $changed = implode(',',    $changed);
        /* neue Informationen in DB speichern */
        $sql = "update projekte set $changed where id=$id";
        $db->query($sql);
        if ($db->count() > 0) {
            $message = "Die Projektinformationen wurden erfolgreich geändert.<br /><a href=\"index.php?site=projekt_display&id=$id\">Projekt öffnen</a>";
            $messageType = 'success';
        } else {
            $message = 'Es ist ein Fehler aufgetreten! '.mysql_error().'<br />'.$sql;
            $messageType = 'error';
        }
    } else {
        $message = 'Es ist ein Fehler aufgetreten! '.mysql_error().'<br />'.$sql;
        $messageType = 'error';
    }
}

/* Informationen zum Projekt auslesen */
$sql = "select * from projekte where id=$id";
$db->query($sql);
if ($db->count() > 0) {
    $res = $db->fetchRow();
    /* Formularfelder definieren */
    $fields = array(
        array(
            'name' => 'KundeId',
            'class' => 'kundeID',
            'disableAutocomplete' => true,
            'input' => array(
                    'name' => 'kundeID',
                    'value' => $res['kundeID'],
                ),
            ),
        array(
            'name' => 'Name',
            'input' => array(
                    'name' => 'name',
                    'value' => $res['name'],
                ),
            ),
        array(
            'name' => 'Stunden gesamt',
            'input' => array(
                    'name' => 'stundenGesamt',
                    'value' => $res['stundenGesamt'],
                ),
            ),
        array(
            'name' => 'Hinweis',
            'textarea' => array(
                    'name' => 'hinweis',
                    'value' => $res['hinweis'],
                ),
            ),
        array(
            'name' => 'Status',
            'select' => array(
                    'name' => 'status',
                    'selected' => $res['status'],
                    'options' => array(
                            array(
                                'value' => 'in Bearbeitung',
                                'text' => 'in Bearbeitung',
                            ),
                            array(
                                'value' => 'fertig',
                                'text' => 'fertig',
                            ),
                        ),
                ),
            ),
        );

    /* Formulardefinition an Smarty übergeben */
    $smarty->assign('fields', $fields);
} else {
    $message = 'Es ist ein Fehler aufgetreten! '.mysql_error().'<br />'.$sql;
    $messageType = 'error';
}
if (isset($message)) {
    $smarty->assign('message', $message);
    $smarty->assign('messageType', $messageType);
}
