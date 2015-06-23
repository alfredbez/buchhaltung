<?php

use Laracasts\Integrated\Extensions\Goutte as IntegrationTest;

require_once 'Traits/TestTrait.php';

abstract class AbstractTest extends IntegrationTest {
  use TestTrait;

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
}
