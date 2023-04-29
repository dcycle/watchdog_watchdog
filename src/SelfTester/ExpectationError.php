<?php

namespace Drupal\watchdog_watchdog\SelfTester;

/**
 * Expectation that a requirements array has an error.
 */
class ExpectationError extends Expectation {

  /**
   * {@inheritdoc}
   */
  public function expectedSeverity() : int {
    return 2;
  }

}
