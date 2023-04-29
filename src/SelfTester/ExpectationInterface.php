<?php

namespace Drupal\watchdog_watchdog\SelfTester;

/**
 * Expectation which tests against a requirements array.
 */
interface ExpectationInterface {

  /**
   * Check that a requirements array meets this expectation.
   *
   * @param array $requirements
   *   A requirements array.
   * @param callable $callback
   *   A logging callback.
   */
  public function check(array $requirements, callable $callback);

}
