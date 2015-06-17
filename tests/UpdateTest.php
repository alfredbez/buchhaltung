<?php

require_once 'AbstractTest.php';

class UpdateTest extends AbstractTest {

  /** @test */
  public function it_updates_database()
  {
      $this->visit('index.php?site=update')
           ->see('Updates wurden erfolgreich durchgeführt!')
           ->visit('index.php?site=update')
           ->see('Es gibt keine Updates');
  }

}