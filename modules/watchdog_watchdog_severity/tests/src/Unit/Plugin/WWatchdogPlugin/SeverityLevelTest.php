<?php

namespace Drupal\Tests\watchdog_watchdog_severity\Unit\Plugin\WWatchdogPlugin;

use Drupal\watchdog_watchdog_severity\Plugin\WWatchdogPlugin\SeverityLevel;
use Drupal\watchdog_watchdog\WWatchdogEvent\WWatchdogEventInterface;
use Drupal\watchdog_watchdog_severity\WWatchdogSeverityInterface;
use PHPUnit\Framework\TestCase;

/**
 * Test SeverityLevel.
 *
 * @group watchdog_watchdog
 */
class SeverityLevelTest extends TestCase {

  /**
   * Test for triggersError().
   *
   * @param string $message
   *   The test message.
   * @param bool $previous
   *   The mock previous opinion.
   * @param bool $expected
   *   The new opinion as to whether an error is triggered.
   *
   * @cover ::alter
   * @dataProvider providerTriggersError
   */
  public function testTriggersError(string $message, bool $previous, bool $expected) {
    $object = $this->getMockBuilder(SeverityLevel::class)
      // NULL = no methods are mocked; otherwise list the methods here.
      ->setMethods([
        'wWatchdogSeverity',
      ])
      ->disableOriginalConstructor()
      ->getMock();

    $object->method('wWatchdogSeverity')
      // @codingStandardsIgnoreStart
      ->willReturn(new class($expected) implements WWatchdogSeverityInterface {
        public function __construct(bool $opinion) {
          $this->opinion = $opinion;
        }
        public function severityMatches(int $severity, bool &$opinion) {
          $opinion = $this->opinion;
        }
      });
      // @codingStandardsIgnoreEnd

    $output = $previous;
    // @codingStandardsIgnoreStart
    $opinion = FALSE;
    $object->triggersError(new class implements WWatchdogEventInterface {
      public function severityLevel() : int {
        // Ignored in this test.
        return 0;
      }
    }, $output);
    // @codingStandardsIgnoreEnd

    if ($output != $expected) {
      print_r([
        'message' => $message,
        'output' => $output,
        'expected' => $expected,
      ]);
    }

    $this->assertTrue($output == $expected, $message);
  }

  /**
   * Provider for testTriggersError().
   */
  public function providerTriggersError() {
    return [
      [
        'message' => 'Expecting TRUE, change',
        'previous' => FALSE,
        'expected' => TRUE,
      ],
      [
        'message' => 'Expecting TRUE, no change',
        'previous' => TRUE,
        'expected' => TRUE,
      ],
      [
        'message' => 'Expecting FALSE, change',
        'previous' => TRUE,
        'expected' => FALSE,
      ],
      [
        'message' => 'Expecting FALSE, no change',
        'previous' => FALSE,
        'expected' => FALSE,
      ],
    ];
  }

}
