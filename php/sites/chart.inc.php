<?php

function getMonth($date)
{
    return substr($date, 3, 2);
}
function getYear($date)
{
    return substr($date, 6);
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
while ($row = $db->fetchRow()) {
    $year = getYear($row['rechnungsdatum']);
    $month = getMonth($row['rechnungsdatum']);

    $key = $month.'/'.substr($year, 2);

    if (!in_array($key, $data['rechnungen']['labels'])) {
        $labels_rechnungen[] = $key;
        $data['rechnungen']['labels'][] = $key;
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

$data['rechnungen']['labels'] = "'".implode("','", $data['rechnungen']['labels'])."'";
$data['rechnungen']['monatsumsatz'] = implode(',', $data['rechnungen']['monatsumsatz']);
$data['rechnungen']['belegeProMonat'] = implode(',', $data['rechnungen']['belegeProMonat']);
$data['rechnungen']['belegDurchschnittsbetrag'] = implode(',', $data['rechnungen']['belegDurchschnittsbetrag']);
$data['rechnungen']['gesamtumsatzMonatlich'] = implode(',', $data['rechnungen']['gesamtumsatzMonatlich']);
$data['rechnungen']['gesamtumsatz'] = number_format($data['rechnungen']['gesamtumsatz'], 2, ',', '.').' €';

/* Umsatz pro Monat ermitteln (Angebote) */
$sql = 'select betrag,angebotsdatum from angebote';
$db->query($sql);
while ($row = $db->fetchRow()) {
    $year = getYear($row['angebotsdatum']);
    $month = getMonth($row['angebotsdatum']);

    $key = $month.'/'.substr($year, 2);

    if (!in_array($key, $data['angebote']['labels'])) {
        $labels_angebote[] = $key;
        $data['angebote']['labels'][] = $key;
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

$data['angebote']['labels'] = "'".implode("','", $data['angebote']['labels'])."'";
$data['angebote']['monatsumsatz'] = implode(',', $data['angebote']['monatsumsatz']);
$data['angebote']['belegeProMonat'] = implode(',', $data['angebote']['belegeProMonat']);
$data['angebote']['belegDurchschnittsbetrag'] = implode(',', $data['angebote']['belegDurchschnittsbetrag']);
$data['angebote']['gesamtumsatzMonatlich'] = implode(',', $data['angebote']['gesamtumsatzMonatlich']);
$data['angebote']['gesamtumsatz'] = number_format($data['angebote']['gesamtumsatz'], 2, ',', '.').' €';

$smarty->assign('data', $data);
