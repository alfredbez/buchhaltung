<?php

/* Artikel, Hersteller und Vertreter aus Datenbank lesen */
$db->query('select
		CONCAT(
		herstellerID,
		id) id,
		name Artikel,
		artikelnummer Artikelnummer,
		menge Menge,
		einheit Einheit,
		einzelpreis Einzelpreis,
		gesamtpreis Gesamtpreis,
		lieferzeit Lieferzeit
	from
		artikel');
$keys = array(
    'id',
    'Artikel',
    'Artikelnummer',
    'Menge',
    'Einheit',
    'Einzelpreis',
    'Gesamtpreis',
    'Lieferzeit',
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
