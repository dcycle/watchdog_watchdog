<?php

namespace Drupal\watchdog_watchdog\WWatchdogExtractor;

use Drupal\watchdog_watchdog\WWatchdogEvent\WWatchdogEventInterface;

/**
 * Version 1 of the extractor.
 */
class WWatchdogExtractorV1 extends WWatchdogExtractorBase {

  public function extract() : WWatchdogEventInterface {
    $decoded = $this->decoded;
    return new $decoded['class']($decoded, $this->timestamp());
  }

}
