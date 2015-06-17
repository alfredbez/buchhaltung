<?php

require_once 'WithKundeTest.php';
require_once 'Traits/WithDatabase.php';
require_once 'Traits/WithTextvorlagen.php';
require_once 'php/classes/pdf.inc.php';
require_once 'php/classes/rechnung.inc.php';
require_once 'php/classes/angebot.inc.php';

abstract class AbstractBelegTest extends WithKundeTest {

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
    "ueberschrift"          => "Dummy-Beleg",
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

  protected function createDummy(){
    $this->createKundeViaQuery();
    $this->db()->query( $this->buildBelegSqlQuery() );
    $this->verifyInDatabase( $this->mainTable, $this->dummyBelegData );
    $id = mysql_insert_id();
    $this->dummyArtikelData[ $this->positionenIdField ] = $id;
    for ($i=0; $i < count($this->dummyArtikelData['name']); $i++) {
      $this->db()->query( $this->buildArticleSqlQuery($i) );
      $this->verifyInDatabase( 'positionen', [
          'name' => $this->dummyArtikelData['name'][$i],
          'menge' => $this->dummyArtikelData['amount'][$i],
          'preis' => $this->dummyArtikelData['preis'][$i],
        ] );
    }
    return $this;
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
    $this->insertTextvorlage()
         ->visit("index.php?site={$this->type}_erstellen")
         ->see('Textvorlage einfügen')
         ->see($this->textvorlagenData['titel'])
         ->see($this->textvorlagenData['text']);
  }

  /** @test */
  public function it_follows_link_to_textvorlagen_page()
  {
    $this->visit("index.php?site={$this->type}_erstellen")
         ->follow('klicke hier')
         ->seePageIs('index.php?site=textvorlagen_erstellen');
  }

  /** @test */
  public function it_doesnt_load_textvorlagen_if_there_are_no_ones()
  {
    $this->visit("index.php?site={$this->type}_erstellen")
         ->see('Es gibt noch keine Textvorlagen')
         ->notSee('Textvorlage einfügen');
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
         ->wait(1000)
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
    $this->createKundeViaQuery()
         ->visit("index.php?site={$this->type}_erstellen")
         ->type('1', 'kundennummer')
         ->type('Artikelname', 'name[]')
         ->type('1', 'amount[]')
         ->type('10', 'preis[]')
         ->type($this->dummyBelegDatum['value'], 'lieferdatum')
         ->type($this->dummyBelegDatum['value'], $this->dummyBelegDatum['field'])
         ->clickCss('input[value="sofort"]')
         ->clickCss("button#save")
         ->wait(2000)
         ->seeFile(ROOT_DIR . "export/{$this->type}/1.pdf")
         ->verifyInDatabase( $this->mainTable, ['kundennummer' => 1])
         ->verifyInDatabase('positionen',[
            'name' => 'Artikelname',
            'menge' => 1,
            'preis' => 10,
          ])
          ->closeBrowser();

    $this->visit("index.php?site={$this->mainTable}_ansehen")
         ->snap()
         ->see('11.90') // Betrag brutto
         ->see($this->dummyBelegDatum['value'])
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