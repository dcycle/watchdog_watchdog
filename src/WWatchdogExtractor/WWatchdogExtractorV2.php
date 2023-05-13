<?php

namespace Drupal\watchdog_watchdog\WWatchdogExtractor;

/**
 * Version 2 of the extractor.
 */
class WWatchdogExtractorV2 extends WWatchdogExtractorV1 {

  /**
   * {@inheritdoc}
   */
  public function timestamp() : int {
    return intval($this->decoded['timestamp']);
  }

}
