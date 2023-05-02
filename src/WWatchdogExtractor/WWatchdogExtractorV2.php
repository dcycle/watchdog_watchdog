<?php

namespace Drupal\watchdog_watchdog\WWatchdogExtractor;

use Drupal\watchdog_watchdog\WWatchdogEvent\WWatchdogEventInterface;

/**
 * Version 2 of the extractor.
 */
class WWatchdogExtractorV2 extends WWatchdogExtractorV2 {

  public function timestamp() : int {
    return intval($decoded['timestamp']);
  }

}
