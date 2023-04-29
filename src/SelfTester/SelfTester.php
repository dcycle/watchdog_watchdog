<?php

namespace Drupal\watchdog_watchdog\SelfTester;

use Drupal\watchdog_watchdog\WWatchdog;
use Drupal\Core\Utility\Error;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;

/**
 * Run tests on a running environment.
 */
class SelfTester {

  /**
   * The injected watchdog_watchdog service.
   *
   * @var \Drupal\watchdog_watchdog\WWatchdog
   */
  protected $wWatchdog;

  /**
   * Logger Factory.
   *
   * @var \Drupal\Core\Logger\LoggerChannelInterface
   */
  protected $loggerFactory;

  /**
   * Class constructor.
   *
   * @param \Drupal\watchdog_watchdog\WWatchdog $wWatchdog
   *   The injected watchdog_watchdog service.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $loggerFactory
   *   The injected loggerFactory.
   */
  public function __construct(WWatchdog $wWatchdog, LoggerChannelFactoryInterface $loggerFactory) {
    $this->wWatchdog = $wWatchdog;
    $this->loggerFactory = $loggerFactory->get('watchdog_watchdog');
  }

  /**
   * Print some test result to screen.
   */
  public function print($mixed) {
    if (is_string($mixed)) {
      print_r($mixed . PHP_EOL);
    }
    else {
      print_r($mixed);
    }
  }

  /**
   * Run some self-tests on this module.
   */
  public function selfTest() {
    $this->print('Starting self-test');
    $num_plugins = count($this->wWatchdog->plugins());
    $expecting = 1;
    if ($num_plugins != $expecting) {
      $this->print('Error: we expect to have ' . $expecting . ' plugin, we have ' . $num_plugins);
      exit(1);
    }

    $this->wWatchdog->reset();

    $this->assertAndReset(new ExpectationNothingToReport());

    $this->tryWithError();

    $this->tryWithException();

    $this->print('Ending self-test');
  }

  /**
   * Make sure the requirements appear as expected, then reset.
   *
   * @param \Drupal\watchdog_watchdog\SelfTester\ExpectationInterface $expectation
   *   An expectation of what this requirement should contain.
   */
  public function assertAndReset(ExpectationInterface $expectation) {
    $this->print($requirements = $this->requirements());
    $expectation->check($requirements, function(string $str) {
      $this->print($str);
    });
    $this->wWatchdog->reset();
  }

  /**
   * Get the requirements.
   *
   * Use strings instead of markup elements, so we can print them to the
   * console.
   *
   * @return array
   *   The requirements as an array.
   */
  public function requirements() : array {
    $candidate = $this->wWatchdog->hookRequirements();

    foreach (['title', 'description', 'value'] as $key) {
      $candidate['watchdog_watchdog'][$key] = strval($candidate['watchdog_watchdog'][$key]);
    }

    return $candidate;
  }

  /**
   * Self test the system with an error.
   */
  public function tryWithError() {
    $this->loggerFactory->error('Hello, this is an error.');
    $this->assertAndReset(new ExpectationError());
  }

  /**
   * Self test the system with an exception.
   */
  public function tryWithException() {
    $decoded = Error::decodeException(new \Exception('hello, this is an exception.'));
    $this->loggerFactory->error('%type: @message in %function (line %line of %file).', $decoded);
    $this->assertAndReset(new ExpectationException());
  }

}
