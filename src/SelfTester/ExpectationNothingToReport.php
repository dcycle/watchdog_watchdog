<?php

namespace Drupal\watchdog_watchdog\SelfTester;

class ExpectationNothingToReport extends Expectation {

  public function expectedSeverity() : int {
    return 0;
  }

}
