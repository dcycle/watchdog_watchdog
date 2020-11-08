<?php

namespace Drupal\Tests\watchdog_watchdog\Unit\Annotation;

use Drupal\watchdog_watchdog\Annotation\WWatchdogPluginAnnotation;
use PHPUnit\Framework\TestCase;

/**
 * Test WWatchdogPluginAnnotation.
 *
 * @group watchdog_watchdog
 */
class WWatchdogPluginAnnotationTest extends TestCase {

  /**
   * Smoke test.
   */
  public function testSmoke() {
    $object = $this->getMockBuilder(WWatchdogPluginAnnotation::class)
      // NULL = no methods are mocked; otherwise list the methods here.
      ->setMethods(NULL)
      ->disableOriginalConstructor()
      ->getMock();

    $this->assertTrue(is_object($object));
  }

}
