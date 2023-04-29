<?php

namespace Drupal\watchdog_watchdog\SelfTester;

class ExpectationException extends Expectation {

  public function expectedSeverity() : int {
    return 2;
  }

}
