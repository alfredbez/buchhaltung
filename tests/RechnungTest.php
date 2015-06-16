<?php

require_once 'AbstractBelegTest.php';

class RechnungTest extends AbstractBelegTest {

  protected $mainTable = 'rechnungen';
  protected $positionenIdField = 'rechnungID';
  protected $type = 'rechnung';
  protected $primaryKeyField = 'rechnungsnummer';
  protected $dummyBelegDatum = [
    'field' => 'rechnungsdatum',
    'value' => '01.01.2015',
  ];


}