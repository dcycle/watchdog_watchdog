<?php

namespace Drupal\watchdog_watchdog\WWatchdogEvent;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\watchdog_watchdog\Utilities\DependencyInjectionTrait;
use Drupal\watchdog_watchdog\Utilities\Utilities;

/**
 * Represents a watchdog event.
 */
class WWatchdogEventBase implements WWatchdogEventInterface {

  use StringTranslationTrait;
  use DependencyInjectionTrait;
  use Utilities;

  /**
   * Keys which should exist in the data.
   *
   * For example, system events and Throwable events should contain different
   * keys.
   *
   * @var array
   */
  protected $keyValidators;

  /**
   * The timestamp of this event.
   *
   * @var int
   */
  protected $timestamp;

  /**
   * The backtrace for this event.
   *
   * @var array
   */
  protected $backtrace;

  /**
   * Constructor.
   *
   * @param array $data
   *   Data pertaining to this event. Each event class has a different data
   *   structure, which is closely related to how Drupal manages log data.
   * @param int $timestamp
   *   The current time, as a unix timestamp.
   * @param array $backtrace
   *   The backtrace representated as an array.
   */
  public function __construct(array $data, int $timestamp, array $backtrace) {
    $this->timestamp = $timestamp;
    $this->backtrace = $backtrace;
    foreach ($this->dataKeyValidators() as $key => $validate) {
      if (!array_key_exists($key, $data)) {
        throw new \Exception('Data key ' . $key . ' must exist in ' . json_encode($data) . '.');
      }
      if (!$validate($data[$key])) {
        throw new \Exception('Data value does not validate for ' . $key);
      }
      $this->keyValidators[$key] = $data[$key];
    }
  }

  /**
   * {@inheritdoc}
   */
  public function encode() : string {
    return json_encode($this->toArray());
  }

  /**
   * The time at which this event happened.
   *
   * @return int
   *   The time at which this event happened.
   */
  public function timestamp() : int {
    return $this->timestamp;
  }

  /**
   * {@inheritdoc}
   */
  public function humanTime() : string {
    $candidate = $this->timestamp();
    return $candidate ? date("Y-m-d h:i:sa", $candidate) . " UTC" : $this->t('Unknown time');
  }

  /**
   * Returns validators for each key which should exist in data.
   *
   * @return array
   *   Each array item is keyed by the key which should exist in data. And its
   *   value is a validator for that data.
   *
   * @throws \Exception
   */
  public function dataKeyValidators() : array {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function report() : bool {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function requirementsSeverity() : int {
    $triggersError = FALSE;
    $this->wWatchdog()->plugins()->triggersError($this, $triggersError);
    // In PHPStan, getting "Ternary operator condition is always false.",
    // although this does not seem to be the case, given that we let plugins
    // manipulate the $triggersError variable.
    // @phpstan-ignore-next-line
    return $this->requirementSeverityStringToInt($triggersError ? 'REQUIREMENT_ERROR' : 'REQUIREMENT_OK');
  }

  /**
   * Return the severity level.
   *
   * In some cases REQUIREMENT_INFO is not defined, for example, if you
   * call drush ev "watchdog_watchdog()->hookRequirements();", so
   * we can are using mapping from
   * https://api.drupal.org/api/drupal/core%21includes%21install.inc/10.
   *
   * @param string $level
   *   A severity such as 'REQUIREMENT_INFO', or 'REQUIREMENT_OK'.
   *
   * @return int
   *   The corresponding severity level as an int.
   */
  public function requirementSeverityStringToInt(string $level) : int {
    switch ($level) {
      case 'REQUIREMENT_ERROR':
        return 2;

      case 'REQUIREMENT_INFO':
        return -1;

      case 'REQUIREMENT_OK':
        return 0;

      case 'REQUIREMENT_WARNING':
        return 1;

      default:
        throw new \Exception('Unknown requirement severity string ' . $level);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function requirementsValue() : string {
    $noErrorString = $this->t('Nothing to report');
    $candidate = new FormattableMarkup($this->extractString('message', $noErrorString), $this->requirementsContext());
    if ($candidate != $noErrorString) {
      $candidate = $this->humanTime() . ' ' . $candidate . ' ' . $this->link($this->t('Get details'), 'system.logging_settings');
    }
    return $candidate;
  }

  /**
   * Get the context for parsing a string for the requireents.
   *
   * The context is the array of replacement strings for the error description.
   * For example, '%line' in the exception string might be replaced with the
   * actual line in which the error occurred. Exceptions provide extra info
   * in the context which is not used in the base string, for example,
   * 'severity_level' exists in the context, but it is not used to replace
   * anything in the base string. If used as is, the context will result in
   * warnings such as:
   *
   * Invalid placeholder (severity_level) with string.
   *
   * This method cleans up the context to remove keys which are not used, thus
   * avoiding such warnings.
   *
   * @return array
   *   The context, cleaned up and ready for use with requirements.
   */
  public function requirementsContext() : array {
    $modifiedContext = $fullContext = $this->extractArray('context');

    foreach ([
      'severity_level',
      'backtrace',
      'exception',
      'channel',
      'link',
      'uid',
      'request_uri',
      'referer',
      'ip',
      'timestamp',
    ] as $key) {
      unset($modifiedContext[$key]);
    }

    return $modifiedContext;
  }

  /**
   * Like ::extract(), but when you know the value is a string.
   */
  public function extractString(string $key, string $default = '') : string {
    return $this->extract($key, $default);
  }

  /**
   * Like ::extract(), but when you know the value is an array.
   */
  public function extractArray(string $key, array $default = []) : array {
    return $this->extract($key, $default);
  }

  /**
   * Extract a value from a key in the array representation of this event.
   *
   * Events are stored as arrays in the database; when rebuilding an event from
   * its array representation, we can then extract values from array keys.
   *
   * @param string $key
   *   An array key whose value to extract.
   * @param mixed $default
   *   The default value to use if the key is not available.
   *
   * @return mixed
   *   The value, or default.
   */
  public function extract(string $key, $default) {
    $array = $this->toArray();

    return array_key_exists($key, $array) ? $array[$key] : $default;
  }

  /**
   * {@inheritdoc}
   */
  public function severityLevel() : int {
    // By default events are of the lowest severity possible.
    return PHP_INT_MAX;
  }

  /**
   * Get the backtrace, if available.
   *
   * @return array
   *   The backtrace.
   */
  public function backtrace() : array {
    return $this->backtrace;
  }

  /**
   * {@inheritdoc}
   */
  public function toArray() : array {
    $return = [];

    $return['version'] = '3';
    $return['class'] = get_class($this);
    $return['timestamp'] = $this->timestamp();
    $return['backtrace'] = $this->backtrace();

    foreach ($this->dataKeyValidators() as $key => $validate) {
      $return[$key] = $this->keyValidators[$key];
      if (!$validate($return[$key])) {
        throw new \Exception('Data value does not validate for ' . $key);
      }
    }

    return $return;
  }

}
