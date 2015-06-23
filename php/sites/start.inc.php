<?php

function getMonth($date)
{
    return substr($date, 3, 2);
}
function getYear($date)
{
    return substr($date, 6);
}
function limitData($data, $limit = 8)
{
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            $data[$key] = array_slice($data[$key], -1 * $limit, $limit);
        }
    }

    return $data;
}

/*
    Folgende Dinge werden ermittelt:
        GesamtumsatzMonatlich): Gesamtumsatz bis zu diesem Monat
        Monatsumsatz: Umsatz, der nur in diesem Monat erzielt wurde
        BelegeProMonat: Anzahl der Rechnungen bzw Belege, die in diesem Monat erstellt wurden
        BelegDurchschnittsbetrag: Durchschnittlicher Betrag aller Belege dieses Monats
*/
$sql = 'select betrag,rechnungsdatum from rechnungen';
$db->query($sql);
$data['rechnungen'] = [
  'labels' => [],
  'gesamtumsatz' => 0,
  'monatsumsatz' => [],
  'gesamtumsatzMonatlich' => [],
  'belegDurchschnittsbetrag' => [],
  'belegeProMonat' => [],
];
while ($row = $db->fetchRow()) {
    $year = getYear($row['rechnungsdatum']);
    $month = getMonth($row['rechnungsdatum']);

    $key = $month.'/'.substr($year, 2);

    if (!in_array($key, $data['rechnungen']['labels'])) {
        $labels_rechnungen[] = $key;
        $data['rechnungen']['labels'][] = $key;
    }

    if (!isset($data['rechnungen']['monatsumsatz'][$key])) {
        $data['rechnungen']['monatsumsatz'][$key] = 0;
    }

    if (!isset($data['rechnungen']['belegeProMonat'][$key])) {
        $data['rechnungen']['belegeProMonat'][$key] = 0;
    }

    $data['rechnungen']['gesamtumsatz']                += $row['betrag'];
    $data['rechnungen']['monatsumsatz'][$key]          += $row['betrag'];
    $data['rechnungen']['gesamtumsatzMonatlich'][$key] = $data['rechnungen']['gesamtumsatz'];
    $data['rechnungen']['belegeProMonat'][$key]        += 1;
}

for ($i = 0; $i < count($data['rechnungen']['labels']); ++$i) {
    $key = $data['rechnungen']['labels'][$i];
    $data['rechnungen']['belegDurchschnittsbetrag'][$key] = $data['rechnungen']['monatsumsatz'][$key] / $data['rechnungen']['belegeProMonat'][$key];
}
$data['rechnungen'] = limitData($data['rechnungen']);
$data['rechnungen']['labels'] = "'".implode("','", $data['rechnungen']['labels'])."'";
$data['rechnungen']['monatsumsatz'] = implode(',', $data['rechnungen']['monatsumsatz']);
$data['rechnungen']['belegeProMonat'] = implode(',', $data['rechnungen']['belegeProMonat']);
$data['rechnungen']['belegDurchschnittsbetrag'] = implode(',', $data['rechnungen']['belegDurchschnittsbetrag']);
$data['rechnungen']['gesamtumsatzMonatlich'] = implode(',', $data['rechnungen']['gesamtumsatzMonatlich']);
$data['rechnungen']['gesamtumsatz'] = number_format($data['rechnungen']['gesamtumsatz'], 2, ',', '.').' €';

/* Umsatz pro Monat ermitteln (Angebote) */
$sql = 'select betrag,angebotsdatum from angebote';
$db->query($sql);
$data['angebote'] = [
  'labels' => [],
  'gesamtumsatz' => 0,
  'monatsumsatz' => [],
  'gesamtumsatzMonatlich' => [],
  'belegDurchschnittsbetrag' => [],
  'belegeProMonat' => [],
];
while ($row = $db->fetchRow()) {
    $year = getYear($row['angebotsdatum']);
    $month = getMonth($row['angebotsdatum']);

    $key = $month.'/'.substr($year, 2);

    if (!in_array($key, $data['angebote']['labels'])) {
        $labels_angebote[] = $key;
        $data['angebote']['labels'][] = $key;
    }

    if (!isset($data['angebote']['monatsumsatz'][$key])) {
        $data['angebote']['monatsumsatz'][$key] = 0;
    }

    if (!isset($data['angebote']['belegeProMonat'][$key])) {
        $data['angebote']['belegeProMonat'][$key] = 0;
    }

    $data['angebote']['gesamtumsatz']                += $row['betrag'];
    $data['angebote']['monatsumsatz'][$key]          += $row['betrag'];
    $data['angebote']['gesamtumsatzMonatlich'][$key] = $data['angebote']['gesamtumsatz'];
    $data['angebote']['belegeProMonat'][$key]        += 1;
}

for ($i = 0; $i < count($data['angebote']['labels']); ++$i) {
    $key = $data['angebote']['labels'][$i];
    $data['angebote']['belegDurchschnittsbetrag'][$key] = $data['angebote']['monatsumsatz'][$key] / $data['angebote']['belegeProMonat'][$key];
}

$data['angebote'] = limitData($data['angebote']);
$data['angebote']['labels'] = "'".implode("','", $data['angebote']['labels'])."'";
$data['angebote']['monatsumsatz'] = implode(',', $data['angebote']['monatsumsatz']);
$data['angebote']['belegeProMonat'] = implode(',', $data['angebote']['belegeProMonat']);
$data['angebote']['belegDurchschnittsbetrag'] = implode(',', $data['angebote']['belegDurchschnittsbetrag']);
$data['angebote']['gesamtumsatzMonatlich'] = implode(',', $data['angebote']['gesamtumsatzMonatlich']);
$data['angebote']['gesamtumsatz'] = number_format($data['angebote']['gesamtumsatz'], 2, ',', '.').' €';

$smarty->assign('data', $data);
