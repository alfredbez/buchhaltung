<?php

require_once 'WithKundeTest.php';

class KundeTest extends WithKundeTest {

  /** @test */
  public function it_creates_kunde()
  {
      $this->createKunde()
           ->snap()
           ->seePageIs('index.php?site=neuer_Kunde')
           ->snap()
           ->see('Der Kunde wurde erfolgreich hinzugefügt!')
           ->visit('index.php?site=kundenuebersicht')
           ->see($this->data['type']['vorname']);
  }

}