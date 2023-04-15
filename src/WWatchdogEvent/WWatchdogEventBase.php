<?php

namespace Drupal\watchdog_watchdog\WWatchdogEvent;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\watchdog_watchdog\Utilities\FriendTrait;
use Drupal\Tests\watchdog_watchdog\Unit\WWatchdogTestBase;
use Drupal\Component\Render\FormattableMarkup;;

/**
 * Represents a watchdog event.
 */
class WWatchdogEventBase implements WWatchdogEventInterface {

  use FriendTrait;
  use StringTranslationTrait;

  /**
   * The timestamp of this event.
   *
   * @var int
   */
  protected $timestamp;

  /**
   * Constructor.
   *
   * Only callable by the friend class WWatchdogEventFactory.
   *
   * @param array $data
   *   Data pertaining to this event. Each event class has a different data
   *   structure, which is closely related to how Drupal manages log data.
   * @param int $timestamp
   *   The current time, as a unix timestamp.
   */
  public function __construct(array $data, int $timestamp) {
    $this->friendAccess([
      WWatchdogEventFactory::class,
      WWatchdogTestBase::class,
    ]);
    $this->timestamp = $timestamp;
    foreach ($this->dataKeyValidators() as $key => $validate) {
      if (!array_key_exists($key, $data)) {
        throw new \Exception('Data key ' . $key . ' must exist.');
      }
      if (!$validate($data[$key])) {
        throw new \Exception('Data value does not validate for ' . $key);
      }
      $this->$key = $data[$key];
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
  public function requirementsDescription() : string {
    return $this->t('Nothing to report');
  }

  /**
   * {@inheritdoc}
   */
  public function requirementsSeverity() : int {
    return $this->requirementSeverityStringToInt('REQUIREMENT_INFO');
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
    return new FormattableMarkup($this->extractString('message', $this->t('Nothing to report')), $this->extractArray('context'), []);
  }

  public function extractString(string $key, string $default = '') : string {
    return $this->extract($key, $default);
  }

  public function extractArray(string $key, array $default = []) : array {
    return $this->extract($key, $default);
  }

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
   * {@inheritdoc}
   */
  public function toArray() : array {
    $return = [];

    $return['class'] = get_class($this);
    $return['timestamp'] = get_class($this);

    foreach ($this->dataKeyValidators() as $key => $validate) {
      $return[$key] = $this->$key;
      if (!$validate($return[$key])) {
        throw new \Exception('Data value does not validate for ' . $key);
      }
    }

    return $return;
  }

}
