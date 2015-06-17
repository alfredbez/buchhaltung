<?php

require_once 'WithDatabase.php';

trait WithKunde {

  use WithDatabase;

  protected $kundeData = [
    'type' => [
      'titel'     => 'Dr.',
      'vorname'   => 'Alfred',
      'nachname'  => 'Bez',
      'adresse'   => 'Stresemannstraße 46',
      'plz'       => '27570',
      'ort'       => 'Bremerhaven',
      'mail'      => 'a.bez@gmail.com',
      'fax'       => '0471/7003502',
      'telefon'   => '0471/7003501',
      'bemerkung' => 'Dies sind nur Testdaten',
    ],
    'select' => [
      'geschlecht' => '0',
    ],
  ];

  protected function createKunde ($data = false)
  {
    $data = $data ?: $this->kundeData;
    $t = $this->visit('index.php?site=neuer_Kunde');
    foreach ($data['type'] as $key => $value) {
      $t = $t->type($value, $key);
    }
    foreach ($data['select'] as $key => $value) {
      $t = $t->select($key, $value);
    }
    $t->press('Speichern');
    return $this;
  }

  protected function insertKunde()
  {
    $data = array_merge($this->kundeData['type'], $this->kundeData['select']);
    $values = $data;
    array_walk($values, function(&$item){
      $item = "'$item'";
    });

    $sql = 'INSERT INTO kunden(';
    $sql .= implode(',', array_keys($data));
    $sql .= ') VALUES(';
    $sql .= implode(',', $values);
    $sql .= ')';

    $this->db()->query($sql);

    $this->verifyInDatabase( 'kunden', [
        'kundennummer' => '1'
      ] );

    return $this;
  }
}