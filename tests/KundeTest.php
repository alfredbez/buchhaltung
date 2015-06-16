<?php

require_once 'WithKundeTest.php';

class KundeTest extends WithKundeTest {

  /** @test */
  public function it_creates_kunde()
  {
      $this->createKunde()
           ->seePageIs('index.php?site=neuer_Kunde')
           ->see('Der Kunde wurde erfolgreich hinzugefügt!')
           ->closeBrowser();
      $this->visit('index.php?site=kundenuebersicht')
           ->see($this->data['type']['vorname']);
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