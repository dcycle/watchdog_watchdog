<?php

namespace Drupal\watchdog_watchdog\WWatchdogExtractor;

use Drupal\watchdog_watchdog\WWatchdogEvent\WWatchdogEventInterface;

/**
 * Interface for an extractor.
 */
interface WWatchdogExtractorInterface {

  /**
   * Extract an event from the json struct.
   *
   * @return \Drupal\watchdog_watchdog\WWatchdogEvent\WWatchdogEventInterface
   *   An event.
   */
  public function extract() : WWatchdogEventInterface;

}
