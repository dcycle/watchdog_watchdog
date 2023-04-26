<?php

namespace Drupal\watchdog_watchdog\WWatchdogEvent;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\watchdog_watchdog\Utilities\FriendTrait;

/**
 * Represents a \Throwable as a watchdog event.
 */
class WWatchdogEventThrowable extends WWatchdogEventSystem {

  use FriendTrait;
  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function severityLevel() : int {
    // Throwables should never happen.
    return 0;
  }

}
