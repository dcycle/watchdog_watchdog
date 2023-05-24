<?php

namespace Drupal\watchdog_watchdog\Plugin\WWatchdogPlugin;

use Drupal\watchdog_watchdog\WWatchdogEvent\WWatchdogEventInterface;
use Drupal\watchdog_watchdog\WWatchdogPlugin\WWatchdogPluginBase;
use Drupal\watchdog_watchdog\Utilities\DependencyInjectionTrait;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Displays the latest event information on /admin/config/development/logging.
 *
 * @WWatchdogPluginAnnotation(
 *   id = "watchdog_watchdog_error_display_form",
 *   description = @Translation("Displays the latest error form."),
 *   weight = -100,
 * )
 */
class WWatchdogAlterErrorDisplayForm extends WWatchdogPluginBase {

  use StringTranslationTrait;
  use DependencyInjectionTrait;

  /**
   * {@inheritdoc}
   */
  public function formAlter(array &$form) {
    $lastEvent = $this->wWatchdog()->lastEvent();
    $errorExists = $lastEvent->report();
    $form['advanced'] = array(
      '#type' => 'details',
      '#title' => $errorExists ? $this->t('Watchdog Watchdog has intercepted an error on @time', [
        '@time' => $lastEvent->humanTime(),
      ]) : $this->t('Watchdog Watchdog has not intercepted any errors'),
      '#open' => FALSE,
    );
    $form['advanced']['description'] = [
      '#type' => 'markup',
      '#markup' => $lastEvent->requirementsValue(),
    ];
    if ($backtrace = $lastEvent->backtrace()) {
      $form['advanced']['backtrace'] = $this->backtrace($backtrace);
    }
  }

  /**
   * Return an event backtrace in the form of a form api table.
   */
  public function backtrace(array $backtrace) : array {
    $ret = array(
      '#type' => 'table',
      '#caption' => $this
        ->t('Sample Table'),
      '#header' => array(
        $this
          ->t('Name'),
        $this
          ->t('Phone'),
      ),
    );
    for ($i = 1; $i <= 4; $i++) {
      $ret[$i]['name'] = array(
        '#type' => 'markup',
        '#markup' => $this
          ->t('Name'),
      );
      $ret[$i]['phone'] = array(
        '#type' => 'markup',
        '#markup' => $this
          ->t('Phone'),
      );
    }
    return $ret;

    return [
      '#type' => 'table',
      '#markup' => $lastEvent->requirementsValue(),
    ];
  }

}
