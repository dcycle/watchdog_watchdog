<?php

namespace Drupal\watchdog_watchdog\Utilities;

trait DependencyInjectionTrait {

  public function wWatchdog() {
    return \Drupal::service('watchdog_watchdog');
  }

}
