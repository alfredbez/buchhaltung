<?php

require_once 'BelegTest.php';

class AngebotTest extends BelegTest {

  protected $mainTable = 'angebote';
  protected $positionenIdField = 'angebotID';
  protected $type = 'angebot';
  protected $primaryKeyField = 'angebotsnummer';
  protected $dummyBelegDatum = [
    'field' => 'angebotsdatum',
    'value' => '01.01.2015',
  ];

}