<?php

namespace Drupal\watchdog_watchdog\SelfTester;

abstract class Expectation implements ExpectationInterface {

  /**
   * {@inheritdoc}
   */
  public function check(array $requirements, callable $callback) {
    $this->checkKeyValue($requirements['watchdog_watchdog'], 'severity', $this->expectedSeverity(), $callback);
    $callback('All good, Joe.');
  }

  public function checkKeyValue($array, $key, $expected, $callback) {
    if ($array[$key] == $expected) {
      $callback($key . ' is ' . $expected . ' as expected.');
    }
    else {
      $callback($key . ' is ' . $array[$key] . ', not ' . $expected . ' as expected.');
      die();
    }
  }

  abstract public function expectedSeverity() : int;

}
