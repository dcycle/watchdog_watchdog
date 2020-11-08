<?php

namespace Drupal\watchdog_watchdog\Whatever;

use Drupal\Component\Utility\Unicode;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\some_module\Whatever\SomeOtherClass;

/**
 * Some class bla bla bla.
 */
class Whatever {

  use StringTranslationTrait;

  /**
   * @return string
   *   A translated string.
   */
  private function request() {
    return $this->t('Hello world.');
  }

}
