<?php

namespace Drupal\watchdog_watchdog\WWatchdogExtractor;

use Drupal\watchdog_watchdog\WWatchdogEvent\WWatchdogEventInterface;

/**
 * Version 1 of the extractor.
 */
class WWatchdogExtractorV1 extends WWatchdogExtractorBase {

  /**
   * {@inheritdoc}
   */
  public function extract() : WWatchdogEventInterface {
    $decoded = $this->decoded;
    return new $decoded['class']($decoded, $this->timestamp());
  }

  /**
   * Get the timestamp associated with this event.
   *
   * @return int
   *   The timestamp associated with this event.
   */
  public function timestamp() : int {
    // In version 1 we we are not storing the timestamp.
    return 0;
  }

  /**
   * Get the backtrace, if available.
   *
   * @return array
   *   The backtrace.
   */
  public function backtrace() : array {
    return [];
  }

}
