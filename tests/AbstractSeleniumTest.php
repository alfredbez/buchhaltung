<?php

use Laracasts\Integrated\Extensions\Selenium as IntegrationTest;
use Laracasts\Integrated\Extensions\Traits\WorksWithDatabase;

require_once 'Traits/TestTrait.php';

abstract class AbstractSeleniumTest extends IntegrationTest {
  use TestTrait, WorksWithDatabase;

  protected function baseUrl()
  {
    return 'http://192.168.56.101/igor';
  }

  public function clear($selector)
  {
    $this->findByNameOrId($selector)->clear();
    return $this;
  }

  protected function findByCssSelector($selector)
  {
      try {
          return $this->session->element('css selector', $selector);
      } catch (NoSuchElement $e) {
          throw new InvalidArgumentException(
              "Couldn't find an element, matching the follwing css selector: '{$seletor}'."
          );
      }
  }

  public function clickCss($selector)
  {
    $this->findByCssSelector($selector)->click();

    return $this;
  }

  public function visit($uri)
  {
      parent::visit($uri);
      $this->session->window($this->session->window_handle())->maximize();

      return $this;
  }

  public function snap($destination = null)
  {
      $destination = $destination ?: $this->getSnapPath();

      parent::snap($destination);

      return $this;
  }
}