<?php

namespace Drupal\watchdog_watchdog_severity;

/**
 * Interface for the watchdog_watchdog_severity service.
 */
interface WWatchdogSeverityInterface {

  /**
   * Provide an opinion on whether the severy matches the threshold.
   *
   * @param int $severity
   *   The severity to check for.
   * @param bool $opinion
   *   The preexisting opinion which can be changed by this function.
   */
  public function severityMatches(int $severity, bool &$opinion);

}
