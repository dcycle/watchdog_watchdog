<?php

namespace Drupal\watchdog_watchdog\WWatchdogEvent;

use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Represents a \Throwable as a watchdog event.
 */
class WWatchdogEventThrowable extends WWatchdogEventSystem {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function severityLevel() : int {
    // Throwables should never happen.
    return 0;
  }

}
