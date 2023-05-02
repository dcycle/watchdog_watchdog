<?php

namespace Drupal\watchdog_watchdog\WWatchdogExtractor;

/**
 * Implements the factory pattern for events.
 */
class WWatchdogExtractorFactory {

  public function getFromDecoded(array $decoded) : WWatchdogExtractorInterface {
    if (!array_key_exists('version', $decoded)) {
      return new WWatchdogExtractorV1($decoded);
    }
    switch ($decoded['version']) {
      case '2':
        return new WWatchdogExtractorV2($decoded);

      default:
        return new WWatchdogExtractorCorruptedArray($decoded);
    }
  }

}
