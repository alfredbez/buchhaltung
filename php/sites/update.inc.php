<?php

$error = '';
/* DB-Update-Skript */
$sql = [];
if (!fieldExists('bezahlt_am', 'rechnungen')) {
    $sql[] = 'ALTER TABLE `rechnungen` ADD `bezahlt_am` text';
}
foreach ($sql as $s) {
    $db->query($s);
    if (mysql_error() !== '') {
        $error .= mysql_error();
        $error .= '<br />'.$s.'<br />';
    }
    if ($error != '') {
        $error .= '<br />';
    }
}
if (!fieldExists('bezahlt_am', 'rechnungen')) {
    $error .= "Das Feld 'bezahlt_am' konnte nicht in die Tabelle 'rechnungen' ";
    $error .= 'eingefügt werden';
    $error .= '<br />'.$s.'<br />';
}
if (count($sql) === 0) {
    $smarty->assign('message', 'Es gibt keine Updates');
    $smarty->assign('messageType', 'success');
} else {
    if ($error != '') {
        $smarty->assign('message', $error);
        $smarty->assign('messageType', 'error');
    } else {
        $smarty->assign('message', 'Updates wurden erfolgreich durchgeführt!');
        $smarty->assign('messageType', 'success');
    }
}
