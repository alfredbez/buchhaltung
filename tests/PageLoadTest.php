<?php

require_once 'AbstractTest.php';

class PageLoadTest extends AbstractTest {

  /** @test */
  public function it_verifies_that_pages_load_properly()
  {
      $this->visit('/')
           ->click('Start')
           ->seePageIs('index.php')
           ->see('Rechnungsbeträge pro Monat')
           ->andSee('Angebotsbeträge pro Monat');
  }

  /** @test */
  public function it_loads_rechnung_overview()
  {
      $this->visit('index.php?site=rechnungen_ansehen')
           ->seePageIs('index.php?site=rechnungen_ansehen')
           ->see('Rechnungen - ansehen');
  }

  /** @test */
  public function it_loads_angebote_overview()
  {
      $this->visit('index.php?site=angebote_ansehen')
           ->seePageIs('index.php?site=angebote_ansehen')
           ->see('Angebote - ansehen');
  }

  /** @test */
  public function it_loads_kunden_overview()
  {
      $this->visit('index.php?site=kundenuebersicht')
           ->seePageIs('index.php?site=kundenuebersicht')
           ->see('Kunden - Übersicht');
  }

}