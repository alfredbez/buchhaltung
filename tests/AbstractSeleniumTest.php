<?php

use Laracasts\Integrated\Extensions\Selenium as IntegrationTest;
use Laracasts\Integrated\Extensions\Traits\WorksWithDatabase;

require_once 'Traits/TestTrait.php';

abstract class AbstractSeleniumTest extends IntegrationTest {
  use TestTrait, WorksWithDatabase;

  protected function baseUrl()
  {
    return 'http://192.168.56.101/igor/';
  }
}