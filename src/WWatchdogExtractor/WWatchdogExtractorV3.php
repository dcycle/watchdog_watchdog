<?php

namespace Drupal\watchdog_watchdog\WWatchdogExtractor;

/**
 * Version 3 of the extractor.
 */
class WWatchdogExtractorV3 extends WWatchdogExtractorV2 {

  /**
   * {@inheritdoc}
   */
  public function backtrace() : array {
    return $this->decoded['backtrace'];
  }

}
