<?php

namespace Drupal\watchdog_watchdog_severity;

use Drupal\Core\Config\ConfigFactory;
use Drupal\watchdog_watchdog\WWatchdog;

/**
 * Service. Use \Drupal::service('watchdog_watchdog_severity').
 */
class WWatchdogSeverity {

  /**
   * The Drupal state variable used for max severity.
   *
   * @var string
   */
  const SEVERITY_VAR = 'watchdog_watchdog_severity';

  /**
   * The ConfigFactory service.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * The WWatchdog service.
   *
   * @var \Drupal\watchdog_watchdog\WWatchdog
   */
  protected $watchdogWatchdog;

  /**
   * Constructs a new WWatchdog object.
   *
   * @param \Drupal\watchdog_watchdog\WWatchdog $watchdog_watchdog
   *   An injected WWatchdog service.
   * @param \Drupal\Core\Config\ConfigFactory $config_factory
   *   An injected ConfigFactory service.
   */
  public function __construct(WWatchdog $watchdog_watchdog, ConfigFactory $config_factory) {
    $this->watchdogWatchdog = $watchdog_watchdog;
    $this->configFactory = $config_factory;
  }

  /**
   * Gets int configuration for this module.
   *
   * @param string $variable
   *   A variable name.
   * @param int $default
   *   A default value in case the configuration does not exist or is not int.
   *
   * @return int
   *   The value of the configuration variable, or the default.
   */
  public function configGetInt(string $variable, int $default) : int {
    $return = $this
      ->configFactory
      ->get('watchdog_watchdog_severity.general.settings')
      ->get($variable);
    return (is_int($return)) ? $return : $default;
  }

  /**
   * Sets int configuration for this module.
   *
   * @param string $variable
   *   A variable name.
   * @param int $value
   *   A value for this configuration.
   */
  public function configSetInt(string $variable, int $value) {
    $config = $this
      ->configFactory
      ->getEditable('watchdog_watchdog_severity.general.settings');
    $config->set($variable, $value)->save();
  }

  /**
   * Sets the maximum allowed severity before which the error is logged.
   *
   * @param int $severity
   *   The maximum allowed severity.
   */
  public function setMaxSeverity(int $severity) {
    $this->configSetInt(self::SEVERITY_VAR, $severity);
  }

  /**
   * Gets the maximum allowed severity before which the error is logged.
   *
   * @return int
   *   The maximum allowed severity.
   */
  public function getMaxSeverity() : int {
    return $this->configGetInt(self::SEVERITY_VAR, $this->watchdogWatchdog::NOTICE);
  }

  /**
   * Check whether the severity level matches what we have in config.
   *
   * This will change the $opinion parameter.
   *
   * @param int $severity
   *   An event severity level (see ./README.md).
   * @param bool $opinion
   *   The current opinion about whether this should trigger an error.
   */
  public function severityMatches(int $severity, bool &$opinion) {
    $opinion = $severity <= $this->getMaxSeverity();
  }

}
