<?php

require_once 'WithKundeTest.php';
require_once 'Traits/WithDatabase.php';
require_once 'Traits/WithTextvorlagen.php';
require_once 'php/classes/pdf.inc.php';
require_once 'php/classes/rechnung.inc.php';

class RechnungTest extends WithKundeTest {

  use WithDatabase, WithTextvorlagen;

  protected $dummyRechnungData = [
    "kundennummer"          => 1,
    "ueberschrift"          => "",
    "rechnungsdatum"        => "01.01.2015",
    "lieferdatum"           => "01.02.2015",
    "text_oben"             => "",
    "text_unten"            => "",
    "abschlag_datum"        => "",
    "abschlag_summe"        => "0.00",
    "endbetrag_typ"         => "brutto",
    "zahlungsart"           => "sofort",
    "skonto_datum"          => "",
    "skonto_prozente"       => "2.00",
    "betrag"                => 0,
  ];

  protected $dummyArtikelData = [
    "name"                  => [
                                  "Artikelname",
                                  "Artikelname 2",
                               ],
    "amount"                => [
                                  "10",
                                  "2",
                               ],
    "einheit"               => [
                                  "Stück",
                                  "Stück",
                               ],
    "preis"                 => [
                                  10.00,
                                  2.00
                               ],
    "angebotID"             => 0,
    "rechnungID"            => 0,
  ];

  protected function buildRechnungSqlQuery()
  {
    $sql = "INSERT INTO rechnungen (";
    $sql .= implode(',', array_keys($this->dummyRechnungData));
    $sql .= ") VALUES (";
    foreach($this->dummyRechnungData as $data)
    {
      $value = "'$data'";
      if(is_int($data))
      {
        $value = "$data";
      }
      $sql .= "$value,";
    }
    // letztes Komma entfernen
    $sql = substr($sql, 0, -1);
    $sql .= ")";
    return $sql;
  }

  protected function buildArticleSqlQuery( $index = 0 )
  {
    $sql = "INSERT INTO positionen (
      name,
      menge,
      einheit,
      preis,
      angebotID,
      rechnungID
    )
    VALUES(
      '{$this->dummyArtikelData['name'][$index]}',
      {$this->dummyArtikelData['amount'][$index]},
      '{$this->dummyArtikelData['einheit'][$index]}',
      '" . $this->dummyArtikelData['preis'][$index] . "',
      {$this->dummyArtikelData['angebotID']},
      {$this->dummyArtikelData['rechnungID']}
    );";
    return $sql;
  }

  protected function generatePdf()
  {
    $id = $this->dummyArtikelData['rechnungID'];

    /* PDF generieren */
    $pdf = new rechnung($id);
    $pdf->Output(ROOT_DIR . 'export/rechnung/' .  $id . '.pdf');

    /* Endpreis in Datenbank speichern */
    $betrag = $pdf->Endpreis();
    $sql = "update rechnungen set betrag=$betrag where rechnungsnummer=$id";
    $this->db()->query($sql);
    return [
      'betrag' => $betrag,
      'id' => $id,
    ];
  }

  /** @test */
  public function it_loads_textvorlagen()
  {
    $this->createKundeViaQuery()
         ->insertTextvorlage()
         ->visit('index.php?site=rechnung_erstellen')
         ->see($this->textvorlagenData['titel'])
         ->see($this->textvorlagenData['text']);
  }

  /** @test */
  public function it_inserts_textvorlage()
  {
    $this->createKundeViaQuery()
         ->insertTextvorlage()
         ->visit('index.php?site=rechnung_erstellen')
         ->type('1', 'kundennummer')
         ->type('1', 'amount[]')
         ->type('10', 'preis[]')
         ->clickCss('input[value="sofort"]')
         ->clickCss("[name='text_oben'] + button")
         ->clickCss("[name='name[]'] + button")
         ->clickCss("[name='text_unten'] + button")
         ->snap()
         ->clickCss("button#save")
         ->waitForElement('info-pdfprint')
         ->snap()
         ->seeFile(ROOT_DIR . 'export/rechnung/1.pdf')
         ->verifyInDatabase('rechnungen',[
            'text_oben' => $this->textvorlagenData['text'],
            'text_unten' => $this->textvorlagenData['text'],
          ])
         ->verifyInDatabase('positionen',[
            'name' => $this->textvorlagenData['text'],
          ]);
  }

  /** @test */
  public function it_creates_rechnung()
  {
    $this->createKundeViaQuery();

    $this->db()->query($this->buildRechnungSqlQuery());
    $this->verifyInDatabase('rechnungen', $this->dummyRechnungData);

    $this->dummyArtikelData["rechnungID"] = mysql_insert_id();

    for ($i=0; $i < count($this->dummyArtikelData['name']); $i++) {
      $this->db()->query($this->buildArticleSqlQuery($i));
    }

    $this->verifyInDatabase('positionen', [
      'rechnungID' => $this->dummyArtikelData["rechnungID"],
      'name' => $this->dummyArtikelData['name'][0],
      'menge' => $this->dummyArtikelData['amount'][0],
      'preis' => $this->dummyArtikelData['preis'][0],
    ]);

    $rechnungData = $this->generatePdf();

    $this->seeFile(ROOT_DIR . 'export/rechnung/' .  $rechnungData['id'] . '.pdf');
    $this->verifyInDatabase('rechnungen', [
      'rechnungsnummer' => $rechnungData['id'],
      'betrag' => $rechnungData['betrag'],
    ]);

    $this->visit('index.php?site=rechnungen_ansehen')
         ->snap()
         ->see('123.76') // Rechnungsbetrag brutto
         ->see($this->dummyRechnungData['rechnungsdatum'])
         ->see($this->dummyArtikelData["rechnungID"])
         ->see($this->data['type']['vorname'] . ' ' . $this->data['type']['nachname']);
  }

  public function tearDown()
  {
    $files = glob(ROOT_DIR . 'export/rechnung/**.pdf');
    foreach($files as $file)
    {
      unlink($file);
    }
  }

}