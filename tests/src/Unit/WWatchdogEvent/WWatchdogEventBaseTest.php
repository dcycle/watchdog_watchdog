<?php

namespace Drupal\Tests\watchdog_watchdog\Unit\WWatchdogEvent;

use Drupal\watchdog_watchdog\WWatchdogEvent\WWatchdogEventBase;
use PHPUnit\Framework\TestCase;

/**
 * Test WWatchdogEventBase.
 *
 * @group watchdog_watchdog
 */
class WWatchdogEventBaseTest extends TestCase {

  /**
   * Smoke test.
   */
  public function testSmoke() {
    $object = $this->getMockBuilder(WWatchdogEventBase::class)
      ->setMethods([])
      ->disableOriginalConstructor()
      ->getMockForAbstractClass();

    $this->assertTrue(is_object($object));
  }

}
