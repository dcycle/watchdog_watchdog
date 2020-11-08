<?php

namespace Drupal\Tests\watchdog_watchdog\Unit\WWatchdogPlugin;

use Drupal\watchdog_watchdog\WWatchdogPlugin\WWatchdogPluginBase;
use PHPUnit\Framework\TestCase;

/**
 * Test WWatchdogPluginBase.
 *
 * @group watchdog_watchdog
 */
class WWatchdogPluginBaseTest extends TestCase {

  /**
   * Smoke test.
   */
  public function testSmoke() {
    $object = $this->getMockBuilder(WWatchdogPluginBase::class)
      ->setMethods([])
      ->disableOriginalConstructor()
      ->getMockForAbstractClass();

    $this->assertTrue(is_object($object));
  }

}
