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
$sql = '';
$sql .= "select
    rechnungen.rechnungsnummer Rechnungsnummer,
    CONCAT(
      kunden.titel,' ',
      kunden.vorname,' ',
      kunden.nachname
    ) Name,
    rechnungen.rechnungsdatum Rechnungsdatum,
    rechnungen.lieferdatum Lieferdatum,
    rechnungen.ueberschrift Überschrift,";
if (fieldExists('bezahlt_am', 'rechnungen')) {
    $sql .= 'rechnungen.bezahlt_am bezahlt_am,';
    $keys['bezahlt_am'] = 'bezahlt am';
}
$sql .= '
    ROUND(rechnungen.betrag,2) Betrag
  from
    rechnungen,kunden
  where
    rechnungen.kundennummer = kunden.kundennummer';
$db->query($sql);
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
