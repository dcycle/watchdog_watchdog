<?php

namespace Drupal\watchdog_watchdog\Utilities;

use Drupal\watchdog_watchdog\WWatchdog;

/**
 * Run tests on a running environment.
 */
class SelfTester {

  /**
   * The injected watchdog_watchdog service.
   *
   * @var \Drupal\watchdog_watchdog\WWatchdog
   */
  protected $wWatchdog;

  /**
   * Class constructor.
   *
   * @param \Drupal\watchdog_watchdog\WWatchdog $wWatchdog
   *   The injected watchdog_watchdog service.
   */
  public function __construct(WWatchdog $wWatchdog) {
    $this->wWatchdog = $wWatchdog;
  }

  /**
   * Print some test result to screen.
   */
  public function print($mixed) {
    if (is_string($mixed)) {
      print_r($mixed . PHP_EOL);
    }
    else {
      print_r($mixed);
    }
  }

  /**
   * Run some self-tests on this module.
   */
  public function selfTest() {
    $this->print('Starting self-test');
    $num_plugins = count($this->wWatchdog->plugins()->plugins());
    if ($num_plugins != 1) {
      $this->print('Error: we expect to have 1 plugin, we have ' . $num_plugins);
      exit(1);
    }
    $this->print('Ending self-test');
  }

}
