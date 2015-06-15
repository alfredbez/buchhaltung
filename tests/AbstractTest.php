<?php

use Laracasts\Integrated\Extensions\Goutte as IntegrationTest;

require_once 'Traits/TestTrait.php';

abstract class AbstractTest extends IntegrationTest {
  use TestTrait;
}