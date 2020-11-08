<?php

namespace Drupal\Tests\watchdog_watchdog\Unit;

use Drupal\watchdog_watchdog\WWatchdog;

/**
 * Test WWatchdog.
 *
 * @group watchdog_watchdog
 */
class WWatchdogTest extends WWatchdogTestBase {

  /**
   * Test for tripped().
   *
   * @param string $message
   *   The test message.
   * @param bool $trip
   *   The result of the mock last message.
   *
   * @cover ::tripped
   * @dataProvider providerTripped
   */
  public function testTripped(string $message, bool $trip) {
    $object = $this->getMockBuilder(WWatchdog::class)
      ->setMethods([
        'stateGetBool',
      ])
      ->disableOriginalConstructor()
      ->getMock();

    $object->method('stateGetBool')
      ->will($this->returnCallback(function (string $state_var, bool $default) use ($trip) : bool {
        if ($state_var != 'watchdog_watchdog_tripped') {
          throw new \Exception('State var watchdog_watchdog_tripped expected');
        }
        if ($default) {
          throw new \Exception('Default must be true');
        }
        return $trip;
      }));

    $output = $object->tripped();

    if ($output != $trip) {
      print_r([
        'message' => $message,
        'output' => $output,
        'expected' => $trip,
      ]);
    }

    $this->assertTrue($output == $trip, $message);
  }

  /**
   * Provider for testTripped().
   */
  public function providerTripped() {
    return [
      [
        'message' => 'Last event trip is TRUE',
        'trip' => TRUE,
      ],
      [
        'message' => 'Last event trip is FALSE',
        'trip' => FALSE,
      ],
    ];
  }

}
