<?php

namespace Drupal\watchdog_watchdog\Logger;

use Drupal\Core\Logger\RfcLoggerTrait;
use Drupal\watchdog_watchdog\WWatchdog;
use Psr\Log\LoggerInterface;

/**
 * The logger which intercepts system messages.
 */
class WWatchdogLogger implements LoggerInterface {

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
   * {@inheritdoc}
   */
  public function log($level, $message, array $context = []) {
    print_r('hello world' . PHP_EOL);
    $this->wWatchdog->intercept($level, $message, $context);
  }

}
