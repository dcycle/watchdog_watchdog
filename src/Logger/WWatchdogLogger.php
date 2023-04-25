<?php

namespace Drupal\watchdog_watchdog\Logger;

use Drupal\Core\Logger\RfcLoggerTrait;
use Drupal\watchdog_watchdog\WWatchdog;
use Psr\Log\LoggerInterface;

/**
 * The logger which intercepts system messages.
 *
 * The whole version_compare nonsense is due to
 * https://www.drupal.org/project/drupal/issues/3354316#comment-15017566.
 * This is ignored by PHPStan in ./scripts/lib/phpstan-drupal/phpstan.neon due
 * to https://github.com/phpstan/phpstan/issues/9229.
 */
// @codingStandardsIgnoreStart
// https://www.drupal.org/project/drupal/issues/3354316.
abstract class WWatchdogLoggerD9andD10common implements LoggerInterface {
// @codingStandardsIgnoreEnd

  use RfcLoggerTrait;

  /**
   * The injected WWatchdog service.
   *
   * @var \Drupal\watchdog_watchdog\WWatchdog
   */
  protected $wWatchdog;

  /**
   * Constructs a new WWatchdog object.
   *
   * @param \Drupal\watchdog_watchdog\WWatchdog $watchdog_watchdog
   *   An injected WWatchdog singleton.
   */
  public function __construct(WWatchdog $watchdog_watchdog) {
    $this->wWatchdog = $watchdog_watchdog;
  }

  /**
   * See https://www.drupal.org/project/drupal/issues/3354316.
   */
  public function logCommonD9andD10($level, $message, array $context = []) {
    $this->wWatchdog->intercept($level, $message, $context);
  }

}

// https://www.drupal.org/project/drupal/issues/3354316.
if (version_compare(\Drupal::VERSION, '10', '>=')) {
  /**
   * See https://www.drupal.org/project/drupal/issues/3354316.
   */
  class WWatchdogLogger extends WWatchdogLoggerD9andD10common {

    /**
     * {@inheritdoc}
     */
    public function log($level, string|\Stringable $message, array $context = []): void {
      $this->logCommonD9andD10($level, $message, $context);
    }

  }
}
else {
  /**
   * See https://www.drupal.org/project/drupal/issues/3354316.
   */
  class WWatchdogLogger extends WWatchdogLoggerD9andD10common {

    /**
     * {@inheritdoc}
     */
    public function log($level, $message, array $context = []) {
      $this->logCommonD9andD10($level, $message, $context);
    }

  }
}
