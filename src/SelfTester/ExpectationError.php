<?php

namespace Drupal\watchdog_watchdog\SelfTester;

class ExpectationError extends Expectation {

  public function expectedSeverity() : int {
    return 2;
  }

}
