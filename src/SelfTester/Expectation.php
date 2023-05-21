<?php

namespace Drupal\watchdog_watchdog\SelfTester;

/**
 * Base expectation which tests against a requirements array.
 */
abstract class Expectation implements ExpectationInterface {

  /**
   * {@inheritdoc}
   */
  public function check(array $requirements, array $backtrace, callable $callback) {
    $this->checkKeyValue($requirements['watchdog_watchdog'], 'severity', '/' . $this->expectedSeverity() . '/', $callback);
    $this->checkBacktrace($backtrace, $callback);
    $callback('All good, Joe.');
  }

  /**
   * Make sure the backtrace corresponds to what is expected.
   *
   * @param array $backtrace
   *   A backtrace.
   * @param callable $callback
   *   A logging callback.
   */
  public function checkBacktrace(array $backtrace, callable $callback) {
    $first = array_shift($backtrace);
    if ($expectedCallingFunction = $this->expectedCallingFunction()) {
      if ($first['function'] != $expectedCallingFunction) {
        $callback('Calling function is expected to be ' . $expectedCallingFunction . ' but is ' . $first['function']);
        die();
      }
      else {
        $callback('Calling function matches ' . $expectedCallingFunction . ' as expected.');
      }
    }
  }

  /**
   * Get the expected name of the calling function, if any.
   *
   * @return string
   *   Expected name of the calling function, if any.
   */
  abstract public function expectedCallingFunction() : string;

  /**
   * Check that a key has a specific value.
   *
   * @param array $requirements
   *   A requirements array.
   * @param string $key
   *   A key such as 'severity'.
   * @param string $expected
   *   An expected grep pattern.
   * @param callable $callback
   *   A logging callback.
   */
  public function checkKeyValue(array $requirements, string $key, string $expected, callable $callback) {
    if (preg_match($expected, $requirements[$key])) {
      $callback($key . ' matches ' . $expected . ' as expected.');
    }
    else {
      $callback($key . ' is ' . $requirements[$key] . ', which does not match ' . $expected . ' as expected.');
      die();
    }
  }

  /**
   * Get the expected severity.
   *
   * @return int
   *   The expected severity, for example 0 or 2.
   */
  abstract public function expectedSeverity() : int;

}
