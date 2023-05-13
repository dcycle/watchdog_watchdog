<?php

namespace Drupal\watchdog_watchdog\WWatchdogDisplayer;

/**
 * Base displayer for an event.
 */
class WWatchdogCommandLineDisplayer extends WWatchdogDisplayer {

  /**
   * Print all information about the associated event.
   */
  public function printAll() {
    $this->print('************************');
    $this->print($this->event->toArray());
    $this->print('************************');
  }

  /**
   * Print a string or anything else.
   *
   * @param mixed $item
   *   A string or array or anything printable.
   */
  public function print(mixed $item) {
    if (is_string($item)) {
      print($item . PHP_EOL);
    }
    else {
      print_r($item);
    }
  }

}
