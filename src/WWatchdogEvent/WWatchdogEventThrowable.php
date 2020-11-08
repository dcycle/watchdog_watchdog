<?php

namespace Drupal\watchdog_watchdog\WWatchdogEvent;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\watchdog_watchdog\Utilities\FriendTrait;

/**
 * Represents a \Throwable as a watchdog event.
 */
class WWatchdogEventThrowable extends WWatchdogEventBase {

  use FriendTrait;
  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function dataKeyValidators() : array {
    return [
      'message' => function ($x) {
        return !empty($x);
      },
      'file' => function ($x) {
        return !empty($x);
      },
      'line' => function ($x) {
        return is_int($x);
      },
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function severityLevel() : int {
    // Throwables should never happen.
    return 0;
  }

}
