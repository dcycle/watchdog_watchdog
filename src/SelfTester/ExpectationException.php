<?php

namespace Drupal\watchdog_watchdog\SelfTester;

/**
 * Expectation that a requirements array has an exception.
 */
class ExpectationException extends Expectation {

  /**
   * {@inheritdoc}
   */
  public function expectedSeverity() : int {
    return 2;
  }

  /**
   * {@inheritdoc}
   */
  public function expectedCallingFunction() : string {
    return 'error';
  }

}
