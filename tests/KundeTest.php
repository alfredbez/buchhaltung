<?php

require_once 'AbstractSeleniumTest.php';
require_once 'Traits/WithKunde.php';
require_once 'Traits/WithDeletesMethod.php';

class KundeTest extends AbstractSeleniumTest {

  use WithKunde, WithDeletesMethod;

  /** @test */
  public function it_creates_kunde()
  {
      $this->createKunde()
           ->seePageIs('index.php?site=neuer_Kunde')
           ->see('Der Kunde wurde erfolgreich hinzugefügt!')
           ->closeBrowser();
      $this->visit('index.php?site=kundenuebersicht')
           ->see($this->kundeData['type']['vorname']);
  }

  /** @test */
  public function it_deletes_kunde()
  {
      $this->insertKunde()
           ->visit('index.php?site=kundenuebersicht')
           ->see($this->kundeData['type']['vorname'])
           ->deleteAndCheckOn('kundenuebersicht');
  }

  /** @test */
  public function it_doesnt_create_empty_kunde()
  {
      $data = ['type' => [], 'select' => []];
      $this->createKunde($data)
           ->seePageIs('index.php?site=neuer_Kunde')
           ->see('Bitte gib einen Titel, Vorname oder Nachnamen ein!')
           ->closeBrowser();
      $this->visit('index.php?site=kundenuebersicht')
           ->see('Keine Einträge vorhanden.');
  }

}
