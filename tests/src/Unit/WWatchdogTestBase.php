<?php

namespace Drupal\Tests\watchdog_watchdog\Unit;

use Drupal\watchdog_watchdog\WWatchdogEvent\WWatchdogEventBase;
use Drupal\watchdog_watchdog\WWatchdogEvent\WWatchdogEventInterface;
use PHPUnit\Framework\TestCase;

/**
 * Base class for testing.
 */
class WWatchdogTestBase extends TestCase {

  /**
   * Get dummy (mock) event.
   *
   * @return \Drupal\watchdog_watchdog\WWatchdogEvent\WWatchdogEventInterface
   *   A dummy (mock) event.
   */
  public function mockEvent() : WWatchdogEventInterface {
    return new WWatchdogEventBase([], 0);
  }

}
