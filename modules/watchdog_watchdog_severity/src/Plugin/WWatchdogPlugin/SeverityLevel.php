<?php

namespace Drupal\watchdog_watchdog_severity\Plugin\WWatchdogPlugin;

use Drupal\watchdog_watchdog\WWatchdogPlugin\WWatchdogPluginBase;
use Drupal\watchdog_watchdog\WWatchdogEvent\WWatchdogEventInterface;
use Drupal\watchdog_watchdog_severity\WWatchdogSeverityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Allows users to filter by severity level.
 *
 * @WWatchdogPluginAnnotation(
 *   id = "watchdog_watchdog_plugin_severity",
 *   description = @Translation("Trigger errors by severity level."),
 *   weight = 1,
 * )
 */
class SeverityLevel extends WWatchdogPluginBase {

  /**
   * The WWatchdogSeverity injected service.
   *
   * @var \Drupal\watchdog_watchdog_severity\WWatchdogSeverityInterface
   */
  protected $wWatchdogSeverity;

  /**
   * Class constructor.
   *
   * @param \Drupal\watchdog_watchdog_severity\WWatchdogSeverityInterface $wWatchdogSeverity
   *   The WWatchdogSeverity service.
   */
  public function __construct(WWatchdogSeverityInterface $wWatchdogSeverity) {
    $this->wWatchdogSeverity = $wWatchdogSeverity;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // Instantiates this form class.
    // @phpstan-ignore-next-line
    return new static(
      // Load the service required to construct this class.
      $container->get('watchdog_watchdog_severity')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function triggersError(WWatchdogEventInterface $event, bool &$opinion) {
    $this->wWatchdogSeverity()->severityMatches($event->severityLevel(), $opinion);
  }

  /**
   * Get the WWatchdogSeverity service.
   *
   * @return \Drupal\watchdog_watchdog_severity\WWatchdogSeverityInterface
   *   The WWatchdogSeverity service.
   */
  public function wWatchdogSeverity() : WWatchdogSeverityInterface {
    return $this->wWatchdogSeverity;
  }

}
