<?php

$keys = [
  'Rechnungsnummer' => 'Rechnungsnummer',
  'Name' => 'Name',
  'Rechnungsdatum' => 'Rechnungsdatum',
  'Lieferdatum' => 'Lieferdatum',
  'Überschrift' => 'Überschrift',
  'Betrag' => 'Betrag',
  'bezahlt_am' => 'bezahlt am',
];
$db->query("SELECT
    rechnungen.rechnungsnummer Rechnungsnummer,
    CONCAT(
      kunden.titel,' ',
      kunden.vorname,' ',
      kunden.nachname
    ) Name,
    rechnungen.rechnungsdatum Rechnungsdatum,
    rechnungen.lieferdatum Lieferdatum,
    rechnungen.ueberschrift Überschrift,
    rechnungen.bezahlt_am bezahlt_am,
    ROUND(rechnungen.betrag,2) Betrag
  FROM
    rechnungen,kunden
  WHERE
    rechnungen.deleted = 0
    AND rechnungen.kundennummer = kunden.kundennummer");
$res = array();
while ($row = $db->fetchRow()) {
    $temp = [];
    foreach ($keys as $key => $prettyKey) {
        $temp[$key] = $row[$key];
    }
    $res[] = $temp;
}
$smarty->assign('res', $res);
$smarty->assign('keys', $keys);
