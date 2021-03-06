<?php

require_once 'AbstractSeleniumTest.php';
require_once 'Traits/WithDatabase.php';
require_once 'Traits/WithTextvorlagen.php';
require_once 'Traits/WithDeletesMethod.php';

class TextvorlagenTest extends AbstractSeleniumTest {

  use WithDatabase, WithTextvorlagen, WithDeletesMethod;

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
           ->deleteAndCheckOn('textvorlagen_uebersicht');
  }

  /** @test */
  public function it_does_print_error_if_there_are_no_updates()
  {
      $this->insertTextvorlage()
           ->visit('index.php?site=textvorlagen_bearbeiten&id=1')
                ->clear('text')
                ->clear('titel')
                 ->submitForm('Speichern', [
                    'text'  => $this->textvorlagenData['text'],
                    'titel' => $this->textvorlagenData['titel'],
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