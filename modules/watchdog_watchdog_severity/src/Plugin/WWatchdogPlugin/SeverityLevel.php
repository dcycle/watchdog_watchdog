<?php

namespace Drupal\watchdog_watchdog_severity\Plugin\WWatchdogPlugin;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
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
final class SeverityLevel extends WWatchdogPluginBase implements ContainerFactoryPluginInterface {

  /**
   * The WWatchdogSeverity injected service.
   *
   * @var \Drupal\watchdog_watchdog_severity\WWatchdogSeverityInterface
   */
  protected $wWatchdogSeverity;

  /**
   * Class constructor.
   *
   * @param array $configuration
   *   See https://www.drupal.org/docs/drupal-apis/services-and-dependency-injection/dependency-injection-in-plugin-block.
   * @param string $plugin_id
   *   See https://www.drupal.org/docs/drupal-apis/services-and-dependency-injection/dependency-injection-in-plugin-block.
   * @param mixed $plugin_definition
   *   See https://www.drupal.org/docs/drupal-apis/services-and-dependency-injection/dependency-injection-in-plugin-block.
   * @param \Drupal\watchdog_watchdog_severity\WWatchdogSeverityInterface $wWatchdogSeverity
   *   The WWatchdogSeverity service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, WWatchdogSeverityInterface $wWatchdogSeverity) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->wWatchdogSeverity = $wWatchdogSeverity;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
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
