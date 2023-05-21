<?php

namespace Drupal\watchdog_watchdog\WWatchdogEvent;

use Drupal\Component\Datetime\Time;
use Drupal\watchdog_watchdog\WWatchdogPlugin\WWatchdogPluginCollection;
use Drupal\watchdog_watchdog\Utilities\DependencyInjectionTrait;

/**
 * Implements the factory pattern for events.
 */
class WWatchdogEventFactory {

  use DependencyInjectionTrait;

  const ERROR_LEVEL = 3;

  const LEVELS_OF_BACKTRACE_USED_BY_LOGGING_MECHANISM = 6;

  /**
   * The injected plugins.
   *
   * @var \Drupal\watchdog_watchdog\WWatchdogPlugin\WWatchdogPluginCollection
   */
  protected $plugins;

  /**
   * The injected time object.
   *
   * @var \Drupal\Component\Datetime\Time
   */
  protected $time;

  /**
   * Class constructor.
   *
   * @param \Drupal\watchdog_watchdog\WWatchdogPlugin\WWatchdogPluginCollection $plugins
   *   The injected plugins.
   * @param \Drupal\Component\Datetime\Time $time
   *   The injected time object.
   */
  public function __construct(WWatchdogPluginCollection $plugins, Time $time) {
    $this->plugins = $plugins;
    $this->time = $time;
  }

  /**
   * Decodes an event which was saved in storage.
   *
   * We cannot use serialize/unserialize because it results in
   * "The database connection is not serializable."
   */
  public function decode(string $encoded) : WWatchdogEventInterface {
    try {
      return $this->extractorFactory()
        ->getFromDecoded($this->jsonDecode($encoded))
        ->extract();
    }
    catch (\Throwable $t) {
      return $this->fromInternalThrowable($t);
    }
  }

  /**
   * Decode an encoded json struct.
   *
   * @param string $encoded
   *   An encocded json string.
   *
   * @return array
   *   A decoded struct.
   */
  public function jsonDecode(string $encoded) : array {
    if ($candidate = json_decode($encoded, TRUE)) {
      return $candidate;
    }
    throw new \Exception('Encoded error is null or is corrupted.');
  }

  /**
   * Get an event based on on an internal \Throwable.
   *
   * @return \Drupal\watchdog_watchdog\WWatchdogEvent\WWatchdogEventInterface
   *   Event based on on an internal \Throwable.
   */
  public function fromInternalThrowable(\Throwable $t) : WWatchdogEventInterface {
    return new WWatchdogEventThrowable([
      'message' => $t->getMessage(),
      'arguments' => [],
      'file' => $t->getFile(),
      'line' => $t->getLine(),
      'context' => [],
      'level' => self::ERROR_LEVEL,
    ], $this->time->getRequestTime(), $this->backtrace());
  }

  /**
   * Constructs a watchdog event.
   *
   * @param mixed $level
   *   An arbitrary level. In practice this will correspond to a number
   *   depending on what was logged, see README.md for details.
   * @param string $message
   *   A message to be logged. For example:
   *   * "Hello, this is an error"
   *   * %type: @message in %function (line %line of %file).
   * @param array $context
   *   A message context. This differs whether we called:
   *   * \Drupal::logger('just_testing')->error('Hello, this is an error'); or
   *   * watchdog_exception('something', new \Exception('hello'));
   *   In the former case we might have array keys like in the README.md.
   *
   * @return \Drupal\watchdog_watchdog\WWatchdogEvent\WWatchdogEventInterface
   *   Event based on a system event.
   */
  public function fromSystemEvent($level, string $message, array $context) : WWatchdogEventInterface {
    return new WWatchdogEventSystem([
      'message' => $message,
      'level' => $level,
      'context' => $context,
    ], $this->time->getRequestTime(), $this->backtrace());
  }

  /**
   * Get a full backtrace without arguments, exluding the logging mechanism.
   *
   * @return array
   *   A full backtrace without arguments, exluding the logging mechanism.
   */
  public function backtrace() : array {
    $ret = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
    for ($i = 0; $i < self::LEVELS_OF_BACKTRACE_USED_BY_LOGGING_MECHANISM; $i++) {
      array_shift($ret);
    }
    // I have found that debub_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS) does not
    // systematically remove args. I have not been able to reproduce it outside
    // Drupal, but to reproduce this within Drpual you can uncomment the
    // following lines and run:
    // drush ev 'print_r(watchdog_watchdog()->eventFactory()->backtrace());'.
    foreach (array_keys($ret) as $index) {
      unset($ret[$index]['args']);
    }
    return $ret;
  }

  /**
   * Get an non-event, i.e. something not worth reporting.
   *
   * @return \Drupal\watchdog_watchdog\WWatchdogEvent\WWatchdogEventInterface
   *   A non-event.
   */
  public function noEvent() : WWatchdogEventInterface {
    return new WWatchdogEventNoEvent([], $this->time->getRequestTime(), []);
  }

}
