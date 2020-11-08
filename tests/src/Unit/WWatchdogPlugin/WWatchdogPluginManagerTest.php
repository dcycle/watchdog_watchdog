<?php

namespace Drupal\Tests\watchdog_watchdog\Unit\WWatchdogPlugin;

use Drupal\watchdog_watchdog\WWatchdogPlugin\WWatchdogPluginManager;
use PHPUnit\Framework\TestCase;

/**
 * Test WWatchdogPluginManager.
 *
 * @group watchdog_watchdog
 */
class WWatchdogPluginManagerTest extends TestCase {

  /**
   * Smoke test.
   */
  public function testSmoke() {
    $object = $this->getMockBuilder(WWatchdogPluginManager::class)
      // NULL = no methods are mocked; otherwise list the methods here.
      ->setMethods(NULL)
      ->disableOriginalConstructor()
      ->getMock();

    $this->assertTrue(is_object($object));
  }

}
