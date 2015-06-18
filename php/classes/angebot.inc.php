<?php

class angebot extends pdf
{
    public $angebot = true;

    public function getBelegdaten()
    {
        $sql = 'select * from angebote where angebotsnummer='.$this->belegnummer;
        $res = mysql_query($sql);
        $num = mysql_num_rows($res);
        $result = array();
        if ($num > 0) {
            $result['kundennummer'] = mysql_result($res, 0, 'kundennummer');
            $result['angebotsdatum'] = mysql_result($res, 0, 'angebotsdatum');
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

    public function setInfoboxdata()
    {
        $data = array(
            'Kunden-Nr' => $this->belegdaten['kundennummer'],
            'Angebots-Nr' => $this->belegnummer,
            'Angebots-Datum' => $this->belegdaten['angebotsdatum'],
        );
        $this->infoboxdata = $data;
    }
}
