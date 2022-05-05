<?php

namespace Drupal\watchdog_watchdog\Plugin\WWatchdogPlugin;

use Drupal\watchdog_watchdog\WWatchdogEvent\WWatchdogEventInterface;
use Drupal\watchdog_watchdog\WWatchdogPlugin\WWatchdogPluginBase;

/**
 * Allows users to filter by severity level.
 *
 * @WWatchdogPluginAnnotation(
 *   id = "watchdog_watchdog_plugin_base_case",
 *   description = @Translation("Trigger errors with sensible defaults."),
 *   weight = -100,
 * )
 */
class WWatchdogBaseCase extends WWatchdogPluginBase {

  const ERROR_SEVERITY = 3;

  /**
   * {@inheritdoc}
   */
  public function triggersError(WWatchdogEventInterface $event, bool &$opinion) {
    $opinion = $event->severityLevel() <= self::ERROR_SEVERITY;
  }

}
