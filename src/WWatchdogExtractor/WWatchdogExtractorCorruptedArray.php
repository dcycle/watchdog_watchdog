<?php

namespace Drupal\watchdog_watchdog\WWatchdogExtractor;

use Drupal\watchdog_watchdog\WWatchdogEvent\WWatchdogEventInterface;

/**
 * A corrupted array.
 */
class WWatchdogExtractorCorruptedArray extends WWatchdogExtractorBase {

  /**
   * The untranslated string representing the reason why this is corrupted.
   *
   * @var string
   */
  protected $reason;

  /**
   * Constructor.
   *
   * @param array $decoded
   *   The decoded array.
   * @param string $reason
   *   Untranslated string representing the reason why this is corrupted.
   */
  public function __construct(array $decoded, string $reason) {
    $this->decoded = $decoded;
    $this->reason = $reason;
  }

  /**
   * {@inheritdoc}
   */
  public function extract() : WWatchdogEventInterface {
    return $this->eventFactory()
      ->fromInternalThrowable(new \Exception('Watchdog Watchdog could not figure out what to do with the struct representing the latest event because ' . $this->reason));
  }

}
