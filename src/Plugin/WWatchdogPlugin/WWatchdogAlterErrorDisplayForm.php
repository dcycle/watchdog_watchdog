<?php

namespace Drupal\watchdog_watchdog\Plugin\WWatchdogPlugin;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\watchdog_watchdog\Utilities\DependencyInjectionTrait;
use Drupal\watchdog_watchdog\WWatchdogPlugin\WWatchdogPluginBase;

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
    $form['advanced'] = [
      '#type' => 'details',
      '#title' => $errorExists ? $this->t('Watchdog Watchdog has intercepted an error on @time', [
        '@time' => $lastEvent->humanTime(),
      ]) : $this->t('Watchdog Watchdog has not intercepted any errors'),
      '#open' => FALSE,
    ];
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
    $ret = [
      '#type' => 'table',
      '#caption' => $this->t('Backtrace. To reset, run drush ev "watchdog_watchdog()->reset()"'),
      '#header' => [
        $this->t('File'),
        $this->t('Line'),
        $this->t('Function'),
      ],
    ];
    $i = 0;
    foreach ($backtrace as $line) {
      $ret[++$i]['file'] = [
        '#type' => 'markup',
        '#markup' => $this->get(['file'], $line),
      ];
      $ret[$i]['line'] = [
        '#type' => 'markup',
        '#markup' => $this->get(['line'], $line),
      ];
      $ret[$i]['function'] = [
        '#type' => 'markup',
        '#markup' => $this->get(['class', 'function'], $line),
      ];
    }
    return $ret;
  }

  /**
   * Get values from an error line.
   *
   * @param array $keys
   *   The keys you want to get, for exmample class and function. These will
   *   be separated by a colon in the return.
   * @param array $line
   *   A line which comes from php's debug_backtrace().
   *
   * @return string
   *   The value of the keys, or "n/a".
   */
  public function get(array $keys, array $line) : string {
    $ret = [];
    foreach ($keys as $key) {
      if (array_key_exists($key, $line)) {
        $ret[] = $line[$key];
      }
    }
    return $ret ? implode(':', $ret) : $this->t('n/a');
  }

}
