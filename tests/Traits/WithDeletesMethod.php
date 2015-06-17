<?php

trait WithDeletesMethod {

  protected function deleteAndCheckOn($overviewSite)
  {
    $this->findByNameOrId('delete')->click();
    $this->wait(500)->findByNameOrId('#sure')->click();
    $this->closeBrowser();
    $this->visit('index.php?site=' . $overviewSite)
         ->see('Keine Einträge vorhanden.');

    return $this;
  }

}