<?php

/* Artikel, Hersteller und Vertreter aus Datenbank lesen */
$db->query('select
		name Name,
		id Id,
		kundennummer Kundennummer,
		telefon Telefon,
		fax Fax,
		passwort Passwort
	from
		hersteller');
$keys = array(
    'Name',
    'Id',
    'Kundennummer',
    'Telefon',
    'Fax',
    'Passwort',
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
