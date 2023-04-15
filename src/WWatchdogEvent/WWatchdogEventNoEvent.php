<?php

namespace Drupal\watchdog_watchdog\WWatchdogEvent;

/**
 * Represents a non-event.
 */
class WWatchdogEventNoEvent extends WWatchdogEventBase {

  /**
   * {@inheritdoc}
   */
  public function report() : bool {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function requirementsSeverity() : int {
    return $this->requirementSeverityStringToInt('REQUIREMENT_OK');
  }

}
