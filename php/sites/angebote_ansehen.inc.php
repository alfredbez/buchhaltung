<?php

$db->query("select
		angebote.angebotsnummer Angebotsnummer,
		CONCAT(
			kunden.titel,' ',
			kunden.vorname,' ',
			kunden.nachname
		) Name,
		angebote.angebotsdatum Angebotsdatum,
		angebote.lieferdatum Lieferdatum,
		angebote.ueberschrift Überschrift,
		ROUND(angebote.betrag,2) Betrag
	from
		angebote,kunden
	where
		angebote.kundennummer = kunden.kundennummer");
$keys = array(
    'Angebotsnummer',
    'Name',
    'Angebotsdatum',
    'Lieferdatum',
    'Überschrift',
    'Betrag',
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
