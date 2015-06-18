<?php

/*	Empfangene Daten speichern	*/
$success = true;
$db->query('select * from einstellungen');
while ($row = $db->fetchRow()) {
    $db_value[$row['name']] = $row['wert'];
}

$num = 0;
$changes = 0;
$debug = '';
foreach ($_POST as $k => $v) {
    if ($k == 'send') {
        continue;
    }    //	Wenn es sich um den Button handelt, dann soll das nächste Element aufgerufen werden
    $$k = $v;                        // $_POST['foo'] = 'bar' wird zu $foo='bar'
    if ($v != $db_value[$k]) {    //	welche Daten wurden geändert?
        ++$changes;
        $db->query("UPDATE einstellungen SET wert = '$v' where name='$k' LIMIT 1");
        if ($db->affected() == 1) {
            ++$num;
        } else {
            $debug .= "K: $k - V: $v - db_var: $db_var<br />";
        }
    }
}
if ($changes == $num) {
    $message = "Ge&auml;nderte Datens&auml;tze: $num";
} else {
    $success = false;
    $message = 'Es ist ein Fehler aufgetreten!<br />'.mysql_error().$debug;
}
$smarty->assign('success', $success);
$smarty->assign('message', $message);

/*	Daten auslesen und an Smarty übergeben	*/
$db->query('select * from einstellungen');
$res = array();
while ($row = $db->fetchRow()) {
    $res[] = array('name' => $row['name'],
                    'wert' => $row['wert'], );
}
$smarty->assign('res', $res);
