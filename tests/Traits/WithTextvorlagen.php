<?php

trait WithTextvorlagen {

  protected $textvorlagenData = ['titel' => 'Dummy-Titel', 'text' => 'Dummy-Text'];

  protected function insertTextvorlage()
  {
    $sql = "INSERT INTO textvorlagen (titel, text) ";
    $sql .= "VALUES (
      '{$this->textvorlagenData['titel']}',
      '{$this->textvorlagenData['text']}'
      )";

    $this->db()->query($sql);

    return $this;
  }
}