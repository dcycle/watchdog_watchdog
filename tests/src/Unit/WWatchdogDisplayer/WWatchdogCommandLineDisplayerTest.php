<?php

namespace Drupal\Tests\watchdog_watchdog\Unit\WWatchdogDisplayer;

use Drupal\watchdog_watchdog\WWatchdogDisplayer\WWatchdogCommandLineDisplayer;
use PHPUnit\Framework\TestCase;

/**
 * Test WWatchdogCommandLineDisplayer.
 *
 * @group watchdog_watchdog
 */
class WWatchdogCommandLineDisplayerTest extends TestCase {

  /**
   * Smoke test.
   */
  public function testSmoke() {
    $object = $this->getMockBuilder(WWatchdogCommandLineDisplayer::class)
      ->setMethods([])
      ->disableOriginalConstructor()
      ->getMockForAbstractClass();

    $this->assertTrue(is_object($object));
  }

}
