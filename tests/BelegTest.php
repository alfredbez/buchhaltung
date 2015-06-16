<?php

require_once 'WithKundeTest.php';
require_once 'Traits/WithDatabase.php';
require_once 'Traits/WithTextvorlagen.php';
require_once 'php/classes/pdf.inc.php';
require_once 'php/classes/rechnung.inc.php';
require_once 'php/classes/angebot.inc.php';

abstract class BelegTest extends WithKundeTest {

  use WithDatabase, WithTextvorlagen;

  /**
   * 'rechnungen' oder 'angebote'
   * @var string
   */
  protected $mainTable = '';

  /**
   * 'rechnungID' oder 'angebotID'
   * @var string
   */
  protected $positionenIdField = '';

  /**
   * 'rechnung' oder 'angebote'
   * wird benutzt um das Objekt zu instanziieren
   * und um den Pfad zu den PDFs zu ermitteln
   * @var string
   */
  protected $type = '';

  /**
   * 'rechnungsnummer' oder 'angebotsnumer'
   * @var string
   */
  protected $primaryKeyField = '';

  /**
   * array mit folgenden keys
   *   field   -   Feldname der Datenbank (z.B. 'rechnungsdatum')
   *   value   -   Dummy-Wert (z.B. 01.01.2015)
   * @var array
   */
  protected $dummyBelegDatum = [
    'field' => '',
    'value' => '',
  ];

  protected $dummyBelegData = [
    "kundennummer"          => 1,
    "ueberschrift"          => "",
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

  protected function buildBelegSqlQuery()
  {
    $sql = "INSERT INTO {$this->mainTable} (";
    $sql .= implode(',', array_keys($this->dummyBelegData));
    $sql .= ",{$this->dummyBelegDatum['field']}";
    $sql .= ") VALUES (";
    foreach($this->dummyBelegData as $data)
    {
      $value = "'$data'";
      if(is_int($data))
      {
        $value = "$data";
      }
      $sql .= "$value,";
    }
    $sql .= "'{$this->dummyBelegDatum['value']}'";
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
    $id = $this->dummyArtikelData[ $this->positionenIdField ];

    /* PDF generieren */
    $pdf = new $this->type($id);
    $pdf->Output(ROOT_DIR . "export/{$this->type}/" .  $id . ".pdf");

    /* Endpreis in Datenbank speichern */
    $betrag = $pdf->Endpreis();
    $sql = "update {$this->mainTable} set betrag=$betrag where {$this->primaryKeyField}=$id";
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
         ->visit("index.php?site={$this->type}_erstellen")
         ->see($this->textvorlagenData['titel'])
         ->see($this->textvorlagenData['text']);
  }

  /** @test */
  public function it_inserts_textvorlage()
  {
    $this->createKundeViaQuery()
         ->insertTextvorlage()
         ->visit("index.php?site={$this->type}_erstellen")
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
         ->seeFile(ROOT_DIR . "export/{$this->type}/1.pdf")
         ->verifyInDatabase( $this->mainTable ,[
            'text_oben' => $this->textvorlagenData['text'],
            'text_unten' => $this->textvorlagenData['text'],
          ])
         ->verifyInDatabase('positionen',[
            'name' => $this->textvorlagenData['text'],
          ]);
  }

  /** @test */
  public function it_creates_beleg()
  {
    $this->createKundeViaQuery();

    $this->db()->query($this->buildBelegSqlQuery());
    $this->verifyInDatabase( $this->mainTable , $this->dummyBelegData);

    $this->dummyArtikelData[ $this->positionenIdField ] = mysql_insert_id();

    for ($i=0; $i < count($this->dummyArtikelData['name']); $i++) {
      $this->db()->query($this->buildArticleSqlQuery($i));
    }

    $this->verifyInDatabase('positionen', [
      $this->positionenIdField => $this->dummyArtikelData[$this->positionenIdField],
      'name' => $this->dummyArtikelData['name'][0],
      'menge' => $this->dummyArtikelData['amount'][0],
      'preis' => $this->dummyArtikelData['preis'][0],
    ]);

    $belegData = $this->generatePdf();

    $this->seeFile(ROOT_DIR . "export/{$this->type}/" .  $belegData['id'] . ".pdf");
    $this->verifyInDatabase( $this->mainTable , [
      $this->primaryKeyField => $belegData['id'],
      'betrag' => $belegData['betrag'],
    ]);

    $this->visit("index.php?site={$this->mainTable}_ansehen")
         ->snap()
         ->see('123.76') // Betrag brutto
         ->see($this->dummyBelegDatum['value'])
         ->see($this->dummyArtikelData[ $this->positionenIdField ])
         ->see($this->data['type']['vorname'] . ' ' . $this->data['type']['nachname']);
  }

  /**
   * Removes generated PDFs, once the test completes.
   *
   * @tearDown
   * @return void
   */
  public function deleteGeneratedPdfs()
  {
    $path = realpath(ROOT_DIR . 'export/');

    $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
    $counter = 0;
    foreach($objects as $name => $object){
        if(is_file($name) && substr($name, -3) === 'pdf'){
          unlink($name);
        }
    }
  }

}