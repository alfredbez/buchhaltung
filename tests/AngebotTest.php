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

}