<?php

namespace Drupal\watchdog_watchdog\WWatchdogEvent;

/**
 * Represents a watchdog event.
 */
interface WWatchdogEventInterface {

  /**
   * Encode this event so it can be placed in a state variable.
   *
   * @return string
   *   Encoded version of this event.
   */
  public function encode() : string;

  /**
   * Whether or not this event should report an error in the requirements.
   *
   * @return bool
   *   TRUE if should report an error in the requirements.
   */
  public function report() : bool;

  /**
   * Get the severity of this event.
   *
   * @return int
   *   Severity of this event, for example REQUIREMENT_ERROR or REQUIREMENT_OK.
   */
  public function requirementsSeverity() : int;

  /**
   * Get the translated value of this event.
   *
   * @return string
   *   Translated value of this event.
   */
  public function requirementsValue() : string;

  /**
   * Return a severity level, lower is worse.
   *
   * See ./README.md.
   *
   * @return int
   *   A severity level.
   */
  public function severityLevel() : int;

  /**
   * Transform this event into a keyed array.
   *
   * @return array
   *   A keyed-array.
   */
  public function toArray() : array;

}
