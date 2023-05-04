<?php

namespace Drupal\watchdog_watchdog\WWatchdogExtractor;

/**
 * Implements the factory pattern for events.
 */
class WWatchdogExtractorFactory {

  /**
   * Get an extractor associated with the decoded struct.
   *
   * @return \Drupal\watchdog_watchdog\WWatchdogExtractor\WWatchdogExtractorInterface
   *   An extractor.
   */
  public function getFromDecoded(array $decoded) : WWatchdogExtractorInterface {
    if (!array_key_exists('version', $decoded)) {
      return new WWatchdogExtractorV1($decoded);
    }
    switch ($decoded['version']) {
      case '2':
        return new WWatchdogExtractorV2($decoded);

      default:
        return new WWatchdogExtractorCorruptedArray($decoded, "the struct's version number, " . $decoded['version'] . ', is unknown to us');
    }
  }

}
