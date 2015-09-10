<?php

class pdf extends fpdf\FPDF
{
    public $printversion = false;
    public $angebot = false;
    public $posY = 130;
    public $preis = 0;
    public $adresse = '';
    public $aktuelle_seite = 1;
    public $positionen_zaehler = 1;
    public $positionendaten;

    public $belegdaten;     // alle Daten zum Beleg (bzw zur Rechnung/zum Angebot)
  public $belegnummer;
    public $kundendaten;
    public $einstellungen;

    public $debugdateiname = 'debug.txt';

    public $zaeler = 0;

    public $infoboxdata;

    public function __construct($belegnummer)
    {
        $this->belegnummer = $belegnummer;

    /* Einstellungen laden */
    $this->einstellungen = $this->getEinstellungen();
    /* Beleginformationen laden */
    $this->belegdaten = $this->getBelegdaten();
    /* Artikelinformationen laden */
    $this->positionendaten = $this->getpositionendaten();
    /* Kundeninformationen laden */
    $this->kundendaten = $this->getKundendaten();

        parent::__construct('P', 'mm', 'A4');

        $this->SetDisplayMode(100);

        $this->AddPage();

    /* Daten für Infobox sammeln */
    $this->setInfoboxdata();

        $this->Infobox();

        $this->Ueberschrift();

        $this->Text_oben();

        $this->tableHeader();

        foreach ($this->positionendaten as $positionen) {
            $this->Artikel(
        $positionen['name'],
        $positionen['menge'],
        $positionen['preis'],
        $positionen['einheit']
      );
        }

        $this->Endpreis();
    }
    public function getEinstellungen()
    {
        $sql = 'select * from einstellungen';
        $res = mysql_query($sql);
        $num = mysql_num_rows($res);
        $result = array();
        if ($num > 0) {
            for ($i = 0;$i < $num;++$i) {
                $result[strtolower(mysql_result($res, $i, 'name'))] = mysql_result($res, $i, 'wert');
            }
        }

        return $result;
    }
    public function getKundendaten()
    {
        $sql = 'select * from kunden where kundennummer='.$this->belegdaten['kundennummer'];
        $res = mysql_query($sql);
        $result = array();
        $result['geschlecht'] = mysql_result($res, 0, 'geschlecht');
        $result['mail'] = mysql_result($res, 0, 'mail');
        $result['titel'] = mysql_result($res, 0, 'titel');
        $result['vorname'] = mysql_result($res, 0, 'vorname');
        $result['nachname'] = mysql_result($res, 0, 'nachname');
        $result['adresse'] = mysql_result($res, 0, 'adresse');
        $result['plz'] = mysql_result($res, 0, 'plz');
        $result['ort'] = mysql_result($res, 0, 'ort');

        return $result;
    }
    public function getBelegdaten()
    {
        $sql = 'select * from rechnungen where rechnungsnummer='.$this->belegnummer;
        $res = mysql_query($sql);
        $num = mysql_num_rows($res);
        $result = array();
        if ($num > 0) {
            $result['kundennummer'] = mysql_result($res, 0, 'kundennummer');
            $result['rechnungsdatum'] = mysql_result($res, 0, 'rechnungsdatum');
            $result['lieferdatum'] = mysql_result($res, 0, 'lieferdatum');
            $result['ueberschrift'] = mysql_result($res, 0, 'ueberschrift');
            $result['zahlungsart'] = mysql_result($res, 0, 'zahlungsart');
            $result['skonto_prozente'] = mysql_result($res, 0, 'skonto_prozente');
            $result['skonto_datum'] = mysql_result($res, 0, 'skonto_datum');
            $result['abschlag_summe'] = mysql_result($res, 0, 'abschlag_summe');
            $result['abschlag_datum'] = mysql_result($res, 0, 'abschlag_datum');
            $result['text_oben'] = mysql_result($res, 0, 'text_oben');
            $result['text_unten'] = mysql_result($res, 0, 'text_unten');
            $result['endbetrag_typ'] = mysql_result($res, 0, 'endbetrag_typ');
        }

        return $result;
    }
    public function getpositionendaten()
    {
        if ($this->angebot === false) {
            $sql = 'select * from positionen where rechnungID='.$this->belegnummer;
        } else {
            $sql = 'select * from positionen where angebotID='.$this->belegnummer;
        }
        $res = mysql_query($sql);
        $num = mysql_num_rows($res);
        $result = array();
        if ($num > 0) {
            for ($i = 0;$i < $num;++$i) {
                $result[$i]['name'] = mysql_result($res, $i, 'name');
                $result[$i]['menge'] = mysql_result($res, $i, 'menge');
                $result[$i]['einheit'] = mysql_result($res, $i, 'einheit');
                $result[$i]['preis'] = mysql_result($res, $i, 'preis');
            }
        }

        return $result;
    }
    public function getHoehe($string, $zeilenhoehe = 5)
    {
        return substr_count($string, "\n") * $zeilenhoehe;
    }
    public function Header()
    {
        $this->printBG();
        $this->Adresse();
    }
    public function printBG()
    {
        if ($this->printversion !== true) {
            /* Hintergrundbild festlegen */
            $this->Image('bg.jpg', 0, 0, 210, 297);
            /* creditreform logo */
            $this->Image(
                'creditreform.png',
                $this->einstellungen['creditreform_logo_abstand_links'],
                $this->einstellungen['creditreform_logo_abstand_oben'],
                $this->einstellungen['creditreform_logo_breite'],
                $this->einstellungen['creditreform_logo_hoehe']
            );
        } else {
            $this->falzmarken();
        }
    }
    public function tableHeader()
    {
        $this->SetFont('Arial', 'B', $this->einstellungen['schriftgroesse_artikel']);

    /*  Tabellenüberschrift */
    $this->SetXY($this->einstellungen['nummer_vor_artikelname_abstand_links'], $this->posY);
        $this->Cell(30, 5, 'Pos');
        $this->SetXY($this->einstellungen['artikelname_abstand_links'], $this->posY);
        $this->Cell(90, 5, 'Artikel');
        $this->SetXY($this->einstellungen['menge_abstand_links'], $this->posY);
        $this->Cell(90, 5, 'Menge');
        $this->SetXY($this->einstellungen['einheit_abstand_links'], $this->posY);
        $this->Cell(90, 5, 'Einheit');
        $this->SetXY($this->einstellungen['preis_pro_einheit_abstand_links'], $this->posY);
        $this->Cell(90, 5, 'E-Preis');
        $this->SetXY($this->einstellungen['preis_mal_menge_abstand_links'], $this->posY);
        $this->Cell(90, 5, 'G-Preis');

        $this->SetFont('Arial', '', $this->einstellungen['schriftgroesse_artikel']);
        $this->posY += 10;
    }
    public function Footer()
    {
        ++$this->aktuelle_seite;
    }
    public function Adresse()
    {
        /* Titel anpassen */
    $this->kundendaten['titel'] = ($this->kundendaten['titel'] !== '') ? $this->kundendaten['titel']."\n" : $this->kundendaten['titel'];
    /* Anrede erstellen */
    switch ($this->kundendaten['geschlecht']) {
      case 0:
        $anrede = 'Herr ';
        break;
      case 1:
        $anrede = 'Frau ';
        break;
      case 2:
        $anrede = '';
        break;
    }
        $data = $this->kundendaten['titel'].$anrede.$this->kundendaten['vorname'].' '.$this->kundendaten['nachname']."\n".$this->kundendaten['adresse']."\n".$this->kundendaten['plz'].' '.$this->kundendaten['ort'];
        $this->SetFont('Arial', '', $this->einstellungen['schriftgroesse_adresse']);
        $this->SetTextColor(000, 000, 000);
        $this->SetXY($this->einstellungen['adresse_abstand_links'], $this->einstellungen['adresse_abstand_oben']);
        $this->MultiCell($this->einstellungen['adresse_breite'], 5, iconv('UTF-8', 'CP1252', $data));

        $this->adresse = $data;
    }
    public function Ueberschrift()
    {
        /*  Überschrift */
    $this->SetFont('Arial', 'B', $this->einstellungen['schriftgroesse_ueberschriften']);
        $this->SetTextColor(000, 000, 000);
        $this->SetXY($this->einstellungen['ueberschrift_abstand_links'], $this->einstellungen['ueberschrift_abstand_oben']);
        $this->Cell(90, 10, iconv('UTF-8', 'CP1252', $this->belegdaten['ueberschrift']));
    }
    public function Text_oben()
    {
        if ($this->belegdaten['text_oben']) {
            $this->SetFont('Arial', '', $this->einstellungen['schriftgroesse_text_oben']);
            $this->SetTextColor(000, 000, 000);
            $this->SetXY($this->einstellungen['text_oben_abstand_links'], $this->posY);
            $this->MultiCell(178, 5, iconv('UTF-8', 'CP1252', $this->belegdaten['text_oben']));
            $this->posY += $this->getHoehe($this->belegdaten['text_oben']);
            $this->posY += $this->einstellungen['text_oben_abstand_nach_unten'];
        }
    }
    public function Text_unten()
    {
        if ($this->belegdaten['text_unten']) {
            $this->posY += $this->einstellungen['text_unten_abstand_nach_oben'];
            $this->SetFont('Arial', '', $this->einstellungen['schriftgroesse_text_unten']);
            $this->SetTextColor(000, 000, 000);
            $this->SetXY($this->einstellungen['text_unten_abstand_links'], $this->posY);
            $this->MultiCell(140, 5, iconv('UTF-8', 'CP1252', $this->belegdaten['text_unten']), 0, 'C');
            $this->posY += $this->getHoehe($this->belegdaten['text_oben']);
        }
    }
    public function setInfoboxdata()
    {
        $data = array(
      'Kunden-Nr' => $this->belegdaten['kundennummer'],
      'Rechnungs-Nr' => $this->belegnummer,
      'Re-Datum' => $this->belegdaten['rechnungsdatum'],
      'Lieferdatum' => $this->belegdaten['lieferdatum'],
    );
        $this->infoboxdata = $data;
    }
    public function Infobox()
    {
        $data = $this->infoboxdata;
        $rechnung_anzeigen = true;

    /*  oben rechts "Rechnung" fett */
    $this->SetFont('Arial', 'B', $this->einstellungen['schriftgroesse_ueberschriften']);
        $this->SetTextColor(000, 000, 000);
        if ($this->printversion == true) {
            $this->SetXY($this->einstellungen['rechnung_abstand_links'], $this->einstellungen['rechnung_abstand_oben'] - 3);
        } else {
            $this->SetXY($this->einstellungen['rechnung_abstand_links'], $this->einstellungen['rechnung_abstand_oben'] + 4);
        }
        if ($rechnung_anzeigen === true) {
            if ($this->angebot === false) {
                $this->Cell(90, 10, iconv('UTF-8', 'CP1252', 'Rechnung'));
            } else {
                $this->Cell(90, 10, iconv('UTF-8', 'CP1252', 'Angebot'));
            }
        }
    /*  Daten unter "Rechnung"(oben rechts) */
    $this->SetFont('Arial', '', '10');
        $this->SetTextColor(000, 000, 000);
        $i = 1;
        foreach ($data as $k => $v) {
            if ($this->printversion == false) {
                $this->SetXY($this->einstellungen['rechnung_abstand_links'], $this->einstellungen['rechnung_abstand_oben'] + 7 + ($i * 5));
            } else {
                $this->SetXY($this->einstellungen['rechnung_abstand_links'], ($this->einstellungen['rechnung_abstand_oben']) + ($i * 5));
            }
            $this->Cell(90, 5, iconv('UTF-8', 'CP1252', $k));
            if ($this->printversion == false) {
                $this->SetXY($this->einstellungen['preis_mal_menge_abstand_links'], $this->einstellungen['rechnung_abstand_oben'] + 7 + ($i * 5));
            } else {
                $this->SetXY($this->einstellungen['preis_mal_menge_abstand_links'], ($this->einstellungen['rechnung_abstand_oben']) + ($i * 5));
            }
            $this->Cell(90, 5, iconv('UTF-8', 'CP1252', $v));
            ++$i;
        }

        if ($this->printversion == false) {
            $this->SetXY($this->einstellungen['rechnung_abstand_links'], $this->einstellungen['rechnung_abstand_oben'] + 7 + ($i * 5));
        } else {
            $this->SetXY($this->einstellungen['rechnung_abstand_links'], ($this->einstellungen['rechnung_abstand_oben']) + ($i * 5));
        }
        $this->Cell(90, 5, iconv('UTF-8', 'CP1252', 'Seite '.$this->aktuelle_seite));
    }
    public function falzmarken()
    {
        if ($this->einstellungen['falzmarken_anzeigen'] != 0) {
            $this->Line(5, $this->einstellungen['falzmarke_1'], 10, $this->einstellungen['falzmarke_1']);
            $this->Line(5, $this->einstellungen['falzmarke_2'], 10, $this->einstellungen['falzmarke_2']);
        }
    }
    public function debug($msg)
    {
        $datei = fopen($this->debugdateiname, 'a');
        fwrite($datei, $msg."\n--------------------------\n");
        fclose($datei);
    }
    public function Artikel($name, $menge, $preis, $einheit)
    {
        $this->debug('PosY: '.$this->posY);

        $preis = str_replace(',', '.', $preis);
        $menge = str_replace(',', '.', $menge);

        if (($this->posY + $this->getHoehe($name)) > 260) {
            /*  Neue Seite erstellen  */
      $this->SetFont('Arial', 'B', '10');
            $this->SetXY(100, 260);
            $boxlaenge = 36 + (strlen($this->preis) * 2);
            $this->Cell($boxlaenge, 5, iconv('UTF-8', 'CP1252', 'Zwischensumme: '.$this->preis.' €'), 1, 'C');
            $this->AddPage();
            $this->posY = 140;
            $this->SetXY(100, 132);
            $this->Cell($boxlaenge, 5, iconv('UTF-8', 'CP1252', 'Zwischensumme: '.$this->preis.' €'), 1, 'C');

            $this->Infobox();
            if ($this->printversion === true) {
                $this->falzmarken();
            }

      /*  Tabellenüberschrift */
      $this->SetXY($this->einstellungen['nummer_vor_artikelname_abstand_links'], $this->posY);
            $this->Cell(30, 5, iconv('UTF-8', 'CP1252', 'Pos'));
            $this->SetXY($this->einstellungen['artikelname_abstand_links'], $this->posY);
            $this->Cell(90, 5, iconv('UTF-8', 'CP1252', 'Artikel'));
            $this->SetXY($this->einstellungen['menge_abstand_links'], $this->posY);
            $this->Cell(90, 5, iconv('UTF-8', 'CP1252', 'Menge'));
            $this->SetXY($this->einstellungen['einheit_abstand_links'], $this->posY);
            $this->Cell(90, 5, iconv('UTF-8', 'CP1252', 'Einheit'));
            $this->SetXY($this->einstellungen['preis_pro_einheit_abstand_links'], $this->posY);
            $this->Cell(90, 5, iconv('UTF-8', 'CP1252', 'E-Preis'));
            $this->SetXY($this->einstellungen['preis_mal_menge_abstand_links'], $this->posY);
            $this->Cell(90, 5, iconv('UTF-8', 'CP1252', 'G-Preis'));
            $this->posY += 10;
        }

        if ($name == 'Festpreis') {
            $preis = $preis / 1.19;
        }

    /*  Aktuelle Nummer ausgeben  */
    if ($name != 'Festpreis') {
        $this->SetFont('Arial', 'B', $this->einstellungen['schriftgroesse_artikel']);
        $this->SetTextColor(000, 000, 000);
        $this->SetXY($this->einstellungen['nummer_vor_artikelname_abstand_links'], $this->posY);
        $this->MultiCell(90, 5, iconv('UTF-8', 'CP1252', $this->positionen_zaehler.'.)'));
    }

        $position_anfang = $this->GetY();

    /*  Artikelname */
    $this->SetFont('Arial', '', $this->einstellungen['schriftgroesse_artikel']);
        $this->SetTextColor(000, 000, 000);
        $this->SetXY($this->einstellungen['artikelname_abstand_links'], $this->posY);
        $this->MultiCell(60, 5, iconv('UTF-8', 'CP1252', "$name"));

        $position_ende = $this->GetY();

        $artikelname_hoehe = $position_ende - $position_anfang + 5;

        $menge = ($menge == 0) ? 1 : $menge;

        if ($name != 'Festpreis') {

      /*  Menge */
      $this->SetXY($this->einstellungen['menge_abstand_links'], $this->posY);
            if ($menge != '') {
                $this->Cell(90, 5, iconv('UTF-8', 'CP1252', "$menge"));
            }

      /*  Einheit */
      $this->SetXY($this->einstellungen['einheit_abstand_links'], $this->posY);
            if ($menge != '') {
                $this->Cell(90, 5, iconv('UTF-8', 'CP1252', "$einheit"));
            }

      /*  Preis pro Einheit */
      $this->SetXY($this->einstellungen['preis_pro_einheit_abstand_links'], $this->posY);
            if ($preis != '') {
                $this->Cell(20, 5, iconv('UTF-8', 'CP1252', number_format(($preis), 2, ',', '.').' €'), 0, 0, 'R');
            }

      /*  Preis für die jeweilige Menge*/
      $this->SetXY($this->einstellungen['preis_mal_menge_abstand_links'], $this->posY);
            $this->SetFont('Arial', 'B', $this->einstellungen['schriftgroesse_artikel']);
            if ($preis != '') {
                $this->Cell(20, 5, iconv('UTF-8', 'CP1252', number_format(($preis * $menge), 2, ',', '.').' €'), 0, 0, 'R');
            }
            $this->SetFont('Arial', '', $this->einstellungen['schriftgroesse_artikel']);
        } else {
            /*  Festpreis anzeigen */
      $this->SetXY($this->einstellungen['preis_mal_menge_abstand_links'], $this->posY);
            $this->SetFont('Arial', 'B', $this->einstellungen['schriftgroesse_artikel']);
            if ($preis != '') {
                $this->Cell(20, 5, iconv('UTF-8', 'CP1252', number_format($preis, 2, ',', '.').' €'), 0, 0, 'R');
            }
            $this->SetFont('Arial', '', $this->einstellungen['schriftgroesse_artikel']);
        }

        $this->posY = $position_ende + 5;

        $this->preis += ($preis * $menge);

        if ($name != 'Festpreis') {
            ++$this->positionen_zaehler;
        }
    }
    public function Endpreis()
    {
        $zahldaten = $this->belegdaten['zahlungsart'];
        if ($zahldaten == 'skonto') {
            $zahldaten = array($this->belegdaten['skonto_datum'] , $this->belegdaten['skonto_prozente']);
        }
        $this->Line(12, ($this->posY), 190, $this->posY);  // Endlinie

    if (
      $this->posY + $this->getHoehe($this->belegdaten['text_unten']) > 245
      ||
        $this->belegdaten['abschlag_summe'] != '0'
        &&
        $this->belegdaten['abschlag_summe'] != ''
        &&
        $this->posY > 220
      ||
        $this->belegdaten['endbetrag_typ'] == 'netto'
        &&
        $this->posY > 230
      ||
        $this->belegdaten['endbetrag_typ'] == 'netto'
        &&
        $this->belegdaten['abschlag_summe'] != '0'
        &&
        $this->belegdaten['abschlag_summe'] != ''
        &&
        $this->posY > 205) {
        // Neue Seite einfügen
      $this->SetFont('Arial', 'B', '10');
        $this->AddPage();
        $this->posY = 140;

        $this->Infobox();
        if ($this->printversion === true) {
            $this->falzmarken();
        }
    } else {
        $this->posY += 5;
    }

        $netto = $this->preis;
        $brutto = $netto * 1.19;
        $mwst = $brutto - $netto;

    /* formatierte Ausgaben der Beträge */
    $netto_format = iconv('UTF-8', 'CP1252', number_format($netto, 2, ',', '.').' €');
        $mwst_format = iconv('UTF-8', 'CP1252', number_format($mwst, 2, ',', '.').' €');

        $posY_zahlungsinfo = $this->posY;

    /* unterschiedliche Darstellungen für Netto und Brutto */

    if ($this->belegdaten['endbetrag_typ'] == 'brutto') {
        /*  Netto-Summe */
      $this->SetFont('Arial', '', $this->einstellungen['schriftgroesse_artikel']);
        $this->SetTextColor(000, 000, 000);
        $this->SetXY($this->einstellungen['preis_mal_menge_abstand_links'] - 40, $this->posY);
        $this->Cell(90, 5, iconv('UTF-8', 'CP1252', 'Netto-Summe'));
        $this->SetXY($this->einstellungen['preis_mal_menge_abstand_links'], $this->posY);
        $this->Cell(20, 5, $netto_format, 0, 0, 'R');

        $this->posY += 5;

      /*  MwSt 19%  */
      $this->SetFont('Arial', '', $this->einstellungen['schriftgroesse_artikel']);
        $this->SetTextColor(000, 000, 000);
        $this->SetXY($this->einstellungen['preis_mal_menge_abstand_links'] - 40, $this->posY);
        $this->Cell(90, 5, iconv('UTF-8', 'CP1252', 'MwSt 19,00 %'));
        $this->SetXY($this->einstellungen['preis_mal_menge_abstand_links'], $this->posY);
        $this->Cell(20, 5, $mwst_format, 0, 0, 'R');

        $this->posY += 5;

        $this->SetFont('Arial', '', $this->einstellungen['schriftgroesse_artikel']);
        $this->SetTextColor(000, 000, 000);
        $this->SetXY(13, $posY_zahlungsinfo);
        $this->Cell(60, 5, iconv('UTF-8', 'CP1252', 'Zahlung'));
        $posY_zahlungsinfo += 5;
        $this->SetXY(13, $posY_zahlungsinfo);

        $skonto = 0;

        if (is_array($zahldaten)) {
            /*  skonto  */
        $skonto = (($brutto / (($zahldaten[1] / 100) + 1)) - $brutto) * -1;
            $brutto -= $skonto;

        /*  skonto  */
        $this->Cell(62, 5, iconv('UTF-8', 'CP1252', 'bis '.$zahldaten[0].' mit '.$zahldaten[1].'% skonto'));
            $this->SetXY(66, $posY_zahlungsinfo);
            $this->Cell(10, 5, iconv('UTF-8', 'CP1252', '='));
            $this->SetXY(72, $posY_zahlungsinfo);
            $this->Cell(15, 5, iconv('UTF-8', 'CP1252', number_format(($brutto), 2, ',', '.').' €'), 0, 0, 'R');
            $posY_zahlungsinfo += 5;
            $this->SetXY(13, $posY_zahlungsinfo);
            $this->Cell(90, 5, iconv('UTF-8', 'CP1252', 'oder innerhalb 14 Tage'));
            $this->SetXY(66, $posY_zahlungsinfo);
            $this->Cell(10, 5, iconv('UTF-8', 'CP1252', '='));
            $this->SetXY(72, $posY_zahlungsinfo);
            $this->Cell(15, 5, iconv('UTF-8', 'CP1252', number_format(($brutto + $skonto), 2, ',', '.').' €'), 0, 0, 'R');

            $this->posY += 5;
        } else {
            switch ($zahldaten) {
        case 'sofort':
          /*  Zahluing sofort Netto Kasse */
          $this->Cell(90, 5, iconv('UTF-8', 'CP1252', 'sofort Netto Kasse'));
          break;
        case 'zweiwochen':
          /*  Zahluing 2wochen Netto Kasse  */
          $this->Cell(90, 5, iconv('UTF-8', 'CP1252', 'innerhalb 14 Tagen Netto Kasse'));
          break;
        }
        }

      /*  Abschlag BEGIN  */
      if ($this->belegdaten['abschlag_summe'] != '0' && $this->belegdaten['abschlag_summe'] != '') {
          /*  Zwischensumme ausgeben*/
        $this->SetXY($this->einstellungen['preis_mal_menge_abstand_links'] - 40, $this->posY);
          $this->Cell(90, 5, iconv('UTF-8', 'CP1252', 'Zwischensumme'));
          $this->SetXY($this->einstellungen['preis_mal_menge_abstand_links'], $this->posY);
          $this->Cell(20, 5, iconv('UTF-8', 'CP1252', number_format($brutto + $skonto, 2, ',', '.').' €'), 0, 0, 'R');

          $this->posY += 10;

          $abstand_nach_unten = 10; //entspricht zwei Leerzeilen
        $this->SetFont('Arial', '', $this->einstellungen['schriftgroesse_artikel']);
          $this->SetTextColor(000, 000, 000);
          $this->SetXY($this->einstellungen['preis_mal_menge_abstand_links'] - 40, $this->posY);

          $abschlagtext = 'Abschlag';
          if ($this->belegdaten['abschlag_datum'] != '') {
              $abschlagtext .= "\nvom ".$this->belegdaten['abschlag_datum'];
              $abstand_nach_unten += 5;   // wenn ein Datum beim Abschlagstext vorhanden ist, dann soll eine zusätzliche Leerzeile erzeugt werden
          }

          $this->MultiCell(90, 5, iconv('UTF-8', 'CP1252', $abschlagtext));
          $this->SetXY($this->einstellungen['preis_mal_menge_abstand_links'], $this->posY);
          $this->Cell(20, 5, iconv('UTF-8', 'CP1252', number_format($this->belegdaten['abschlag_summe'], 2, ',', '.').' €'), 0, 0, 'R');

          $brutto -= $this->belegdaten['abschlag_summe'];

        /*  Leerzeilen erzeugen */
        $this->posY += $abstand_nach_unten;
      }
      /*  Abschlag END  */

      /*  Rechnungsbetrag */
      $this->SetFont('Arial', 'B', $this->einstellungen['schriftgroesse_artikel']);
        $this->SetTextColor(000, 000, 000);
        $this->SetXY($this->einstellungen['preis_mal_menge_abstand_links'] - 40, $this->posY);
        if ($this->angebot === false) {
            $this->Cell(90, 5, iconv('UTF-8', 'CP1252', 'Rechnungsbetrag:'));
        } else {
            $this->Cell(90, 5, iconv('UTF-8', 'CP1252', 'Angebotsbetrag:'));
        }
        $this->SetXY($this->einstellungen['preis_mal_menge_abstand_links'], $this->posY);
        $this->Cell(20, 5, iconv('UTF-8', 'CP1252', number_format($brutto + $skonto, 2, ',', '.').' €'), 0, 0, 'R');
    } elseif ($this->belegdaten['endbetrag_typ'] == 'netto') {

      /* Netto-Summe */
      $this->SetXY($this->einstellungen['preis_mal_menge_abstand_links'] - 60, $this->posY);
        $this->Cell(90, 5, iconv('UTF-8', 'CP1252', 'Netto-Summe:'));
        $this->SetXY($this->einstellungen['preis_mal_menge_abstand_links'], $this->posY);
        $this->Cell(20, 5, iconv('UTF-8', 'CP1252', number_format($netto, 2, ',', '.').' €'), 0, 0, 'R');

        $this->posY += 5;

      /*  MwSt 0,00%  */
      $this->SetFont('Arial', '', $this->einstellungen['schriftgroesse_artikel']);
        $this->SetTextColor(000, 000, 000);
        $this->SetXY($this->einstellungen['preis_mal_menge_abstand_links'] - 60, $this->posY);
        $this->Cell(90, 5, iconv('UTF-8', 'CP1252', 'MwSt 0,00 %'));
        $this->SetXY($this->einstellungen['preis_mal_menge_abstand_links'], $this->posY);
        $this->Cell(20, 5, iconv('UTF-8', 'CP1252', '0,00 €'), 0, 0, 'R');

        $this->posY += 5;

      /* Zahlungsinfos */
      $this->SetFont('Arial', '', $this->einstellungen['schriftgroesse_artikel']);
        $this->SetTextColor(000, 000, 000);
        $this->SetXY(13, $posY_zahlungsinfo);
        $this->Cell(60, 5, iconv('UTF-8', 'CP1252', 'Zahlung'));
        $posY_zahlungsinfo += 5;
        $this->SetXY(13, $posY_zahlungsinfo);

        $skonto = 0;

        if (is_array($zahldaten)) {
            /*  skonto  */
        $skonto = (($netto / (($zahldaten[1] / 100) + 1)) - $netto) * -1;
            $netto -= $skonto;

        /*  skonto  */
        $this->Cell(62, 5, iconv('UTF-8', 'CP1252', 'bis '.$zahldaten[0].' mit '.$zahldaten[1].'% skonto'));
            $this->SetXY(66, $posY_zahlungsinfo);
            $this->Cell(10, 5, iconv('UTF-8', 'CP1252', '='));
            $this->SetXY(72, $posY_zahlungsinfo);
            $this->Cell(15, 5, iconv('UTF-8', 'CP1252', number_format(($netto), 2, ',', '.').' €'), 0, 0, 'R');
            $posY_zahlungsinfo += 5;
            $this->SetXY(13, $posY_zahlungsinfo);
            $this->Cell(90, 5, iconv('UTF-8', 'CP1252', 'oder innerhalb 14 Tage'));
            $this->SetXY(66, $posY_zahlungsinfo);
            $this->Cell(10, 5, iconv('UTF-8', 'CP1252', '='));
            $this->SetXY(72, $posY_zahlungsinfo);
            $this->Cell(15, 5, iconv('UTF-8', 'CP1252', number_format(($netto + $skonto), 2, ',', '.').' €'), 0, 0, 'R');

            $this->posY += 5;
        } else {
            switch ($zahldaten) {
        case 'sofort':
          /*  Zahluing sofort Netto Kasse */
          $this->Cell(90, 5, iconv('UTF-8', 'CP1252', 'sofort Netto Kasse'));
          break;
        case 'zweiwochen':
          /*  Zahluing 2wochen Netto Kasse  */
          $this->Cell(90, 5, iconv('UTF-8', 'CP1252', 'innerhalb 14 Tagen Netto Kasse'));
          break;
        }
        }

      /*  Abschlag BEGIN  */
      if ($this->belegdaten['abschlag_summe'] != '0' && $this->belegdaten['abschlag_summe'] != '') {
          /*  Zwischensumme ausgeben*/
        $this->SetXY($this->einstellungen['preis_mal_menge_abstand_links'] - 60, $this->posY);
          $this->Cell(90, 5, iconv('UTF-8', 'CP1252', 'Zwischensumme'));
          $this->SetXY($this->einstellungen['preis_mal_menge_abstand_links'], $this->posY);
          $this->Cell(20, 5, iconv('UTF-8', 'CP1252', number_format($netto + $skonto, 2, ',', '.').' €'), 0, 0, 'R');

          $this->posY += 10;

          $abstand_nach_unten = 10; //entspricht zwei Leerzeilen
        $this->SetFont('Arial', '', $this->einstellungen['schriftgroesse_artikel']);
          $this->SetTextColor(000, 000, 000);
          $this->SetXY($this->einstellungen['preis_mal_menge_abstand_links'] - 60, $this->posY);

          $abschlagtext = 'Abschlag';
          if ($this->belegdaten['abschlag_datum'] != '') {
              $abschlagtext .= "\nvom ".$this->belegdaten['abschlag_datum'];
              $abstand_nach_unten += 5;   // wenn ein Datum beim Abschlagstext vorhanden ist, dann soll eine zusätzliche Leerzeile erzeugt werden
          }

          $this->MultiCell(90, 5, iconv('UTF-8', 'CP1252', $abschlagtext));
          $this->SetXY($this->einstellungen['preis_mal_menge_abstand_links'], $this->posY);
          $this->Cell(20, 5, iconv('UTF-8', 'CP1252', number_format($this->belegdaten['abschlag_summe'], 2, ',', '.').' €'), 0, 0, 'R');

          $netto -= $this->belegdaten['abschlag_summe'];

        /*  Leerzeilen erzeugen */
        $this->posY += $abstand_nach_unten;
      }
      /*  Abschlag END  */

      /*  Rechnungsbetrag */
      $this->SetFont('Arial', 'B', $this->einstellungen['schriftgroesse_artikel']);
        $this->SetTextColor(000, 000, 000);
        $this->SetXY($this->einstellungen['preis_mal_menge_abstand_links'] - 60, $this->posY);
        if ($this->angebot === false) {
            $this->Cell(90, 5, iconv('UTF-8', 'CP1252', 'Rechnungsbetrag:'));
        } else {
            $this->Cell(90, 5, iconv('UTF-8', 'CP1252', 'Angebotsbetrag:'));
        }
        $this->SetXY($this->einstellungen['preis_mal_menge_abstand_links'], $this->posY);
        $this->Cell(20, 5, iconv('UTF-8', 'CP1252', number_format($netto + $skonto, 2, ',', '.').' €'), 0, 0, 'R');

      /* Text für Netto-Rechnungen einfügen */
      $this->posY += 10;
        $this->SetXY(30, $this->posY);
        $this->SetFont('Arial', '', $this->einstellungen['schriftgroesse_artikel']);
        $this->MultiCell(160, 5, iconv('UTF-8', 'CP1252', 'Nach § 13b Abs. 1 und 2 UStG sind Sie als Leistungsempfänger Schuldner der Umsatzsteuer (Hinweis nach 14a Abs. 5 Satz 2 UStG).'), 0, 'C');
        $this->posY += 5;
    }

        $this->SetFont('Arial', '', $this->einstellungen['schriftgroesse_artikel']);
        $this->SetTextColor(000, 000, 000);
        $this->posY += 10;
        $this->SetXY(30, $this->posY);
        if ($this->angebot === false) {
            $this->MultiCell(160, 5, iconv('UTF-8', 'CP1252', 'Ich bedanke mich für den Auftrag und wünsche mir auch weiterhin eine gute Zusammenarbeit'), 0, 'C');
        } else {
            $this->MultiCell(160, 5, iconv('UTF-8', 'CP1252', 'Ich hoffe, dass Ihnen mein Angebot zusagt.'), 0, 'C');
        }
        $this->posY += 10;

        $this->Text_unten();

    // Endbetrag zurückgeben
    if ($this->belegdaten['endbetrag_typ'] == 'brutto') {
        return str_replace(',', '', number_format($brutto + $skonto, 2));
    } elseif ($this->belegdaten['endbetrag_typ'] == 'netto') {
        return str_replace(',', '', number_format($netto + $skonto, 2));
    }
    }
}
