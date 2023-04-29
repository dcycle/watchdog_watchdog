<?php

namespace Drupal\watchdog_watchdog\SelfTester;

/**
 * Expectation that a requirements array has no errors to report.
 */
class ExpectationNothingToReport extends Expectation {

  /**
   * {@inheritdoc}
   */
  public function expectedSeverity() : int {
    return 0;
  }

}
