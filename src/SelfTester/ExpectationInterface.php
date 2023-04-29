<?php

namespace Drupal\watchdog_watchdog\SelfTester;

interface ExpectationInterface {

  public function check(array $requirements, callable $callback);

}
