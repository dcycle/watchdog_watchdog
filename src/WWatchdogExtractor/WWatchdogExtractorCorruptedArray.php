<?php

namespace Drupal\watchdog_watchdog\WWatchdogExtractor;

use Drupal\watchdog_watchdog\WWatchdogEvent\WWatchdogEventInterface;

/**
 * A corrupted array.
 */
class WWatchdogExtractorCorruptedArray extends WWatchdogExtractorBase {

  /**
   * {@inheritdoc}
   */
  public function extract() : WWatchdogEventInterface {
    return new WWatchdogEventThrowable([
      'message' => $t->getMessage(),
      'arguments' => [],
      'file' => $t->getFile(),
      'line' => $t->getLine(),
    ], $this->time->getRequestTime());
  }

}
