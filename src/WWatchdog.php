<?php

namespace Drupal\watchdog_watchdog;

use Drupal\watchdog_watchdog\WWatchdogEvent\WWatchdogEventFactory;
use Drupal\watchdog_watchdog\WWatchdogEvent\WWatchdogEventInterface;
use Drupal\watchdog_watchdog\WWatchdogPlugin\WWatchdogPluginCollection;
use Drupal\Core\State\State;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * WWatchdog singleton. Use \Drupal::service('watchdog_watchdog').
 */
class WWatchdog {

  use StringTranslationTrait;

  /**
   * The injected State service.
   *
   * @var \Drupal\Core\State\State
   */
  protected $state;

  /**
   * The WWatchdogEventFactory service.
   *
   * @var \Drupal\watchdog_watchdog\WWatchdogEvent\WWatchdogEventFactory
   */
  protected $wWatchdogEventFactory;

  /**
   * The WWatchdogPluginCollection service.
   *
   * @var \Drupal\watchdog_watchdog\WWatchdogPlugin\WWatchdogPluginCollection
   */
  protected $wWatchdogPluginCollection;

  /**
   * Constructs a new ExposeStatus object.
   *
   * @param \Drupal\watchdog_watchdog\WWatchdogPlugin\WWatchdogPluginCollection $wWatchdogPluginCollection
   *   The WWatchdogPluginCollection service.
   * @param \Drupal\Core\State\State $state
   *   The Sstate service.
   * @param \Drupal\watchdog_watchdog\WWatchdogEvent\WWatchdogEventFactory $wWatchdogEventFactory
   *   The event factory service.
   */
  public function __construct(WWatchdogPluginCollection $wWatchdogPluginCollection, State $state, WWatchdogEventFactory $wWatchdogEventFactory) {
    $this->wWatchdogPluginCollection = $wWatchdogPluginCollection;
    $this->state = $state;
    $this->wWatchdogEventFactory = $wWatchdogEventFactory;
  }

  /**
   * Testable implementation of hook_requirements().
   */
  public function hookRequirements() : array {
    $requirements['watchdog_watchdog'] = [
      'title' => $this->t('Watchdog Watchdog first reported error'),
      'description' => $this->t('If any error occurs during operation of your site, it will be reported here. Further errors are not reported. You can reset watchdog_watchdog by running drush ev "watchdog_watchdog()->reset();"'),
      'value' => $this->lastEvent()->requirementsValue(),
      'severity' => $this->lastEvent()->requirementsSeverity(),
    ];

    return $requirements;
  }

  /**
   * Intercept a message being logged.
   *
   * A message is in the process of being logged. This function is the heart
   * of this module.
   *
   * @param mixed $level
   *   An arbitrary level. In practice this will correspond to a number
   *   depending on what was logged, as in the ./README.md file.
   * @param string $message
   *   A message to be logged.
   * @param array $context
   *   A message context.
   */
  public function intercept($level, string $message, array $context) {
    try {
      if ($this->tripped()) {
        return;
      }
      $event = $this->wWatchdogEventFactory->fromSystemEvent($level, $message, $context);

      $triggers_error = FALSE;

      $this->plugins()->triggersError($event, $triggers_error);

      if ($triggers_error) {
        $this->trip($event);
      }
    }
    catch (\Throwable $t) {
      $this->trip($this->wWatchdogEventFactory->fromInternalThrowable($t));
    }
  }

  /**
   * Get all WWatchdogPlugin plugins.
   *
   * See the modules included in the ./modules directory for an example on how
   * to create a plugin.
   *
   * @return \Drupal\watchdog_watchdog\WWatchdogPlugin\WWatchdogPluginCollection
   *   All plugins.
   *
   * @throws \Exception
   */
  public function plugins() : WWatchdogPluginCollection {
    return $this->wWatchdogPluginCollection;
  }

  /**
   * Reset (untrip) the alert.
   */
  public function reset() {
    return $this->trip($this->wWatchdogEventFactory->noEvent());
  }

  /**
   * Log an event.
   *
   * @param \Drupal\watchdog_watchdog\WWatchdogEvent\WWatchdogEventInterface $event
   *   An event to log.
   */
  public function trip(WWatchdogEventInterface $event) {
    $this->state->set('watchdog_watchdog_last_event', $event->encode());
  }

  /**
   * Get the last event to have been recorded, which might be a non-event.
   *
   * Non-events (nothing to report) are events like any other, but their trip()
   * method returns false().
   *
   * @return \Drupal\watchdog_watchdog\WWatchdogEvent\WWatchdogEventInterface
   *   The last event (or non-event) to have been logged.
   */
  public function lastEvent() : WWatchdogEventInterface {
    try {
      return $this->wWatchdogEventFactory->decode($this->stateGetString('watchdog_watchdog_last_event', $this->wWatchdogEventFactory->noEvent()->encode()));
    }
    catch (\Throwable $t) {
      return $this->wWatchdogEventFactory->fromInternalThrowable($t);
    }
  }

  /**
   * Query the state for a bool, returning a default as a fallback.
   *
   * @param string $state_var
   *   The state variable name.
   * @param bool $default
   *   The default bool.
   *
   * @return bool
   *   The bool from the state variable, or the default.
   */
  public function stateGetBool(string $state_var, bool $default) : bool {
    $candidate = $this->state->get($state_var, $default);
    return is_bool($candidate) ? $candidate : $default;
  }

  /**
   * Query the state for a string, returning a default as a fallback.
   *
   * @param string $state_var
   *   The state variable name.
   * @param string $default
   *   The default string.
   *
   * @return string
   *   The string from the state variable, or the default.
   */
  public function stateGetString(string $state_var, string $default) : string {
    $candidate = $this->state->get($state_var, $default);
    return is_string($candidate) ? $candidate : $default;
  }

  /**
   * Check whether our alert has been tripped.
   *
   * An alert is tripped if the last logged event has tripped the system and
   * triggered an error in hook_requirements().
   *
   * @return bool
   *   Whether an alert is tripped.
   */
  public function tripped() : bool {
    return $this->stateGetBool('watchdog_watchdog_tripped', FALSE);
  }

}
