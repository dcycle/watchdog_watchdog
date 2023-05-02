<?php

namespace Drupal\watchdog_watchdog\WWatchdogExtractor;

use Drupal\watchdog_watchdog\WWatchdogEvent\WWatchdogEventInterface;

/**
 * Interface for an extractor.
 */
interface WWatchdogExtractorInterface {

  public function extract() : WWatchdogEventInterface;

}
