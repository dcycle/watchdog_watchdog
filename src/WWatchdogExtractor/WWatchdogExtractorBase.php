<?php

namespace Drupal\watchdog_watchdog\WWatchdogExtractor;

use Drupal\watchdog_watchdog\WWatchdogEvent\WWatchdogEventInterface;

/**
 * Base class for extractors.
 */
abstract class WWatchdogExtractorBase implements WWatchdogExtractorInterface {

  /**
   * The decoded array.
   *
   * @var array
   */
  protected $decoded;

  /**
   * Constructor.
   *
   * @param array $decoded
   *   The decoded array.
   */
  public function __construct(array $decoded) {
    $this->decoded = $decoded;
  }

}
