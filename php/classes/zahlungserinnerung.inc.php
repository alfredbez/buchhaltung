<?php
class zahlungserinnerung extends pdf
{
    public $belegTitel = 'Zahlungserinnerung';
    public $level = 1;

    public function __construct($id, $level)
    {
        $this->level = $level;
        parent::__construct($id);
    }

    public function setInfoboxdata()
    {
        $this->infoboxdata = [
            'Kunden-Nr' => $this->belegdaten['kundennummer'],
            'Rechnungs-Nr' => $this->belegnummer,
            'Re-Datum' => $this->belegdaten['rechnungsdatum'],
            'Fälligkeitsdatum' => date('d.m.Y', strtotime('+1week')),
            'Mahngebühr' => number_format($this->einstellungen['mahngebuehr' . $this->level], 2, ',', '.').' €',
        ];
    }

    protected function setAbschlussSatz()
    {
        $this->abschlussSatz = 'Sollten Sie den Rechnungsbetrag nicht innerhalb von 7 Tagen überwiesen, dann muss ich den Fall leider an Creditreform weitergeleiten. ';
        $this->abschlussSatz .= "\nZahlungseingänge sind bis zum ";
        $this->abschlussSatz .= date('d.m.Y', strtotime('yesterday'));
        $this->abschlussSatz .= ' berücksichtigt.';
    }
}
