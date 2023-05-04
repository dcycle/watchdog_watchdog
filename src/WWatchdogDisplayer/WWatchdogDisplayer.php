<?php

namespace Drupal\watchdog_watchdog\WWatchdogDisplayer;

use Drupal\watchdog_watchdog\WWatchdogEvent\WWatchdogEventInterface;

/**
 * Base displayer for an event.
 */
class WWatchdogDisplayer implements WWatchdogDisplayerInterface {

  /**
   * The event to be displayed.
   *
   * @var \Drupal\watchdog_watchdog\WWatchdogEvent\WWatchdogEventInterface
   */
  protected $event;

  /**
   * Constructor.
   *
   * @param \Drupal\watchdog_watchdog\WWatchdogEvent\WWatchdogEventInterface $event
   *   The event to be displayed.
   */
  public function __construct(WWatchdogEventInterface $event) {
    $this->event = $event;
  }


}
