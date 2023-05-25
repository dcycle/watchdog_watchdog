<?php

namespace Drupal\watchdog_watchdog\Utilities;

use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * General utilities.
 */
trait Utilities {

  /**
   * Make a link.
   */
  public function link(string $translatedText, string $route) : string {

    // https://drupal.stackexchange.com/a/144995/13414.
    $url = Url::fromRoute($route);
    return Link::fromTextAndUrl($translatedText, $url)->toString();
  }

}
