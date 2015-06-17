<?php

require_once 'AbstractBelegTest.php';

class AngebotTest extends AbstractBelegTest {

  protected $mainTable = 'angebote';
  protected $positionenIdField = 'angebotID';
  protected $type = 'angebot';
  protected $primaryKeyField = 'angebotsnummer';
  protected $dummyBelegDatum = [
    'field' => 'angebotsdatum',
    'value' => '01.01.2015',
  ];

  protected function verify_lieferdatum_in_database($lieferdatum)
  {
    $this->verifyInDatabase( 'rechnungen', [
            'kundennummer' => $this->dummyBelegData['kundennummer'],
            'ueberschrift' => $this->dummyBelegData['ueberschrift'],
            'lieferdatum' => $lieferdatum,
            'endbetrag_typ' => $this->dummyBelegData['endbetrag_typ'],
            'zahlungsart' => $this->dummyBelegData['zahlungsart'],
            'skonto_prozente' => $this->dummyBelegData['skonto_prozente'],
          ]);

    return $this;
  }

  protected function prepare_create_rechnung_from_angebot( $lieferdatum = '')
  {
    $this->visit('index.php?site=rechnungen_ansehen');
    if($lieferdatum !== '') {
      $this->notSee($lieferdatum);
    }
    $this->notSeeFile(ROOT_DIR . "export/rechnung/1.pdf")
         ->closeBrowser();
    $this->createDummy()
         ->visit("index.php?site=angebote_ansehen")
         ->snap()
         ->clickCss('tr td')
         ->waitForElement('rechnung_aus_angebot')
         ->see('Rechnung erstellen')
         ->clickCss('#rechnung_aus_angebot');
    return $this;
  }

  /** @test */
  public function it_doesnt_create_rechnung_from_angebot_with_invalid_lieferdatum()
  {
    $this->prepare_create_rechnung_from_angebot()
         ->typeInPrompt('blabla')
         ->seeInAlert('ist kein gültiges Datum im Format TT.MM.JJJJ');
  }

  /** @test */
  public function it_creates_rechnung_from_angebot_without_lieferdatum()
  {
    $this->prepare_create_rechnung_from_angebot()
         ->typeInPrompt('')
         ->seeInAlert('Soll das im Angebot angegebene Lieferdatum verwendet werden?', false)
         ->dismissAlert()
         ->wait(2000)
         ->seeFile(ROOT_DIR . "export/rechnung/1.pdf")
         ->verify_lieferdatum_in_database('');
  }

  /** @test */
  public function it_creates_rechnung_from_angebot_with_entered_lieferdatum()
  {
    $lieferdatum = '31.12.2015';
    $this->prepare_create_rechnung_from_angebot($lieferdatum)
         ->typeInPrompt($lieferdatum)
         ->wait(2000)
         ->closeBrowser();

    $this->visit('index.php?site=rechnungen_ansehen')
         ->see($lieferdatum)
         ->verify_lieferdatum_in_database($lieferdatum)
         ->seeFile(ROOT_DIR . "export/rechnung/1.pdf");
  }

  /** @test */
  public function it_creates_rechnung_from_angebot_with_lieferdatum_from_angebot()
  {
    $lieferdatum = '01.02.2015';
    $this->prepare_create_rechnung_from_angebot($lieferdatum)
         ->typeInPrompt('')
         ->seeInAlert('Soll das im Angebot angegebene Lieferdatum verwendet werden?')
         ->wait(1500)
         ->closeBrowser();

    $this->visit('index.php?site=rechnungen_ansehen')
         ->see($lieferdatum)
         ->verify_lieferdatum_in_database($lieferdatum)
         ->seeFile(ROOT_DIR . "export/rechnung/1.pdf");
  }

}