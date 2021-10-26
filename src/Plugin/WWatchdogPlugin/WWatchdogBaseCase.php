<?php

namespace Drupal\watchdog_watchdog\Plugin\WWatchdogPlugin;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\watchdog_watchdog\WWatchdogPlugin\WWatchdogPluginBase;
use Drupal\watchdog_watchdog\WWatchdogEvent\WWatchdogEventInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

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

  /**
   * {@inheritdoc}
   */
  public function triggersError(WWatchdogEventInterface $event, bool &$opinion) {
    print_r('hello');
    print_r($event);
    // $this->wWatchdogSeverity()->severityMatches($event->severityLevel(), $opinion);
  }

}
