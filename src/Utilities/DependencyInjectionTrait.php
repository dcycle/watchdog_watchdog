<?php

namespace Drupal\watchdog_watchdog\Utilities;

/**
 * Trait which can be used to fetch mockable services.
 */
trait DependencyInjectionTrait {

  /**
   * Mockable wrapper around the watchdog_watchdog service.
   */
  public function wWatchdog() {
    return \Drupal::service('watchdog_watchdog');
  }

  /**
   * Mockable wrapper around the watchdog_watchdog.extractor_factory service.
   */
  public function extractorFactory() {
    return \Drupal::service('watchdog_watchdog.extractor_factory');
  }

}
