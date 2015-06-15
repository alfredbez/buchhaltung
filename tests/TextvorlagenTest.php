<?php

require_once 'AbstractSeleniumTest.php';
require_once 'Traits/WithDatabase.php';

class TextvorlagenTest extends AbstractSeleniumTest {

  use WithDatabase;

  protected $data = ['titel' => 'Dummy-Titel', 'text' => 'Dummy-Text'];

  protected function insertTextvorlage()
  {
    $sql = "INSERT INTO textvorlagen (titel, text) ";
    $sql .= "VALUES ('{$this->data['titel']}', '{$this->data['text']}')";

    $this->db()->query($sql);

    return $this;
  }

  /** @test */
  public function it_creates()
  {
      $this->visit('index.php?site=textvorlagen_erstellen')
           ->type('Titel', 'titel')
           ->type('Text der Vorlage', 'text')
           ->press('Speichern')
           ->see('Die Textvorlage wurde erfolgreich hinzugefügt!');
  }

  /** @test */
  public function it_doesnt_create_empty()
  {
      $this->visit('index.php?site=textvorlagen_erstellen')
                 ->type('', 'titel')
                 ->type('', 'text')
                 ->press('Speichern')
                 ->see('Bitte gib einen Text ein!');
  }

  /** @test */
  public function it_doesnt_change_text_to_empty()
  {
      $this->insertTextvorlage()
           ->visit('index.php?site=textvorlagen_bearbeiten&id=1')
                 ->clear('text')
                 ->submitForm('Speichern', ['text' => ''])
                 ->snap()
                 ->see('Bitte gib einen Text ein!');
  }

  /** @test */
  public function it_deletes()
  {
      $this->insertTextvorlage()
           ->visit('index.php?site=textvorlagen_uebersicht')
           ->findByNameOrId('delete')->click();
      $this->wait(500)->findByNameOrId('#sure')->click();
      $this->visit('index.php?site=textvorlagen_uebersicht')
           ->see('Keine Einträge vorhanden.');
  }

  /** @test */
  public function it_does_print_error_if_there_are_no_updates()
  {
      $this->insertTextvorlage()
           ->visit('index.php?site=textvorlagen_bearbeiten&id=1')
                ->clear('text')
                ->clear('titel')
                 ->submitForm('Speichern', [
                    'text'  => $this->data['text'],
                    'titel' => $this->data['titel'],
                  ])
                 ->snap()
                 ->see('Du hast keine neuen Angaben gemacht!');
  }

  /** @test */
  public function it_does_change_text()
  {
      $newText = 'Neuer Text';
      $this->insertTextvorlage()
           ->visit('index.php?site=textvorlagen_bearbeiten&id=1')
                 ->type($newText, 'text')
                 ->press('Speichern')
                 ->see($newText)
                 ->see('Die Textvorlage wurde erfolgreich geändert!');
  }

}