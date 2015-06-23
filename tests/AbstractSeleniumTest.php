<?php

use Laracasts\Integrated\Extensions\Selenium as IntegrationTest;
use Laracasts\Integrated\Extensions\Traits\WorksWithDatabase;

require_once 'Traits/TestTrait.php';

abstract class AbstractSeleniumTest extends IntegrationTest {
  use TestTrait, WorksWithDatabase;

  /**
   * Execute the query.
   *
   * @param  \PDOStatement $query
   * @return \PDOStatement
   */
  protected function execute($query)
  {
    $query->execute($this->bindings);

    $this->bindings = [];
    $this->wheres   = [];

    return $query;
  }

  protected function baseUrl()
  {
    return 'http://192.168.56.101/igor';
  }

  public function clear($selector)
  {
    $this->findByNameOrId($selector)->clear();
    return $this;
  }

  public function dismissAlert()
  {
    try {
        $this->session->dismiss_alert();
    } catch (\WebDriver\Exception\NoAlertOpenError $e) {
        throw new PHPUnitException(
            "Well, tried to dismiss the alert, but there wasn't one. Dangit."
        );
    }

    return $this;
  }

  public function typeInPrompt($text, $accept = true)
  {
    try {
        $this->session->postAlert_text(['text' => $text]);
    } catch (\WebDriver\Exception\NoAlertOpenError $e) {
        throw new PHPUnitException(
            "Could not see '{$text}' because no alert box was shown."
        );
    } catch (\WebDriver\Exception\UnknownError $e) {
        // This would only apply to the PhantomJS driver.
        // It seems to have issues with alerts, so I'm
        // not sure what we can do about that...
        return $this;
    }

    if ($accept) {
        $this->acceptAlert();
    }

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
