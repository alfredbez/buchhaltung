<?php

require_once 'BelegTest.php';

class RechnungTest extends BelegTest {

  protected $mainTable = 'rechnungen';
  protected $positionenIdField = 'rechnungID';
  protected $type = 'rechnung';
  protected $primaryKeyField = 'rechnungsnummer';
  protected $dummyBelegDatum = [
    'field' => 'rechnungsdatum',
    'value' => '01.01.2015',
  ];


}