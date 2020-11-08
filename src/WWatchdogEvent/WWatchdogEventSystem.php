<?php

namespace Drupal\watchdog_watchdog\WWatchdogEvent;

/**
 * Represents a watchdog event.
 */
class WWatchdogEventSystem extends WWatchdogEventBase {

  /**
   * {@inheritdoc}
   */
  public function dataKeyValidators() : array {
    return [
      'message' => function ($x) {
        return !empty($x);
      },
      'context' => function ($x) {
        return is_array($x);
      },
      'level' => function ($x) {
        return is_int($x);
      },
    ];
  }

}
