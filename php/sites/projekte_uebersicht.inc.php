<?php

$db->query('select * from projekte');
$keys = array(
    'id',
    'kundeID',
    'name',
    'status',
    'stundenGesamt',
    'stundenBisher',
    'erstellDatum',
    'fertigDatum',
    'hinweis',
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
