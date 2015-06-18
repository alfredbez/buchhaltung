<?php

$db->query('select * from kunden');
$keys = array(
    'kundennummer',
    'titel',
    'vorname',
    'nachname',
    'ort',
    'telefon',
);
$res = array();
while ($row = $db->fetchRow()) {
    $temp = null;
    foreach ($keys as $key) {
        $temp[$key] = $row[$key];
    }
    $res[] = $temp;
}
$smarty->assign('res', $res);
$smarty->assign('keys', $keys);
