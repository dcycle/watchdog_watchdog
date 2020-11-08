<?php

namespace Drupal\Tests\watchdog_watchdog_severity\Unit;

use Drupal\watchdog_watchdog_severity\WWatchdogSeverity;
use PHPUnit\Framework\TestCase;

/**
 * Test WWatchdogSeverity.
 *
 * @group watchdog_watchdog
 */
class WWatchdogSeverityTest extends TestCase {

  /**
   * Smoke test.
   */
  public function testSmoke() {
    $object = $this->getMockBuilder(WWatchdogSeverity::class)
      // NULL = no methods are mocked; otherwise list the methods here.
      ->setMethods(NULL)
      ->disableOriginalConstructor()
      ->getMock();

    $this->assertTrue(is_object($object));
  }

}
