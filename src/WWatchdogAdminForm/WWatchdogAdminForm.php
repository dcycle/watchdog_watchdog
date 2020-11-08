<?php

namespace Drupal\watchdog_watchdog\WWatchdogAdminForm;

use Drupal\Core\Form\FormStateInterface;
use Drupal\watchdog_watchdog\WWatchdog;

/**
 * Represents the watchdog_watchdog admin form.
 */
class WWatchdogAdminForm {

  /**
   * Injected watchdog_watchdog service.
   *
   * @var \Drupal\watchdog_watchdog\WWatchdog
   */
  protected $wWatchdog;

  /**
   * Class constructor.
   *
   * @param \Drupal\watchdog_watchdog\WWatchdog $wWatchdog
   *   Injected watchdog_watchdog service.
   */
  public function __construct(WWatchdog $wWatchdog) {
    $this->wWatchdog = $wWatchdog;
  }

  /**
   * Testable alter hook for the "system_logging_settings" form.
   *
   * @param array $form
   *   The form object.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   */
  public function alter(array &$form, FormStateInterface $form_state) {
    $this->newFunctionalityFromPlugins($form);
    $this->addValidateHandler($form);
    $this->addSubmitHandler($form);
  }

  /**
   * Helper function, adds validation handler to "system_logging_settings" form.
   *
   * @param array $form
   *   The form object.
   */
  public function addValidateHandler(array &$form) {
    $form['#validate'][] = 'watchdog_watchdog_validate_handler';
  }

  /**
   * Helper function, adds submit handler to "system_logging_settings" form.
   *
   * @param array $form
   *   The form object.
   */
  public function addSubmitHandler(array &$form) {
    foreach (array_keys($form['actions']) as $action) {
      if ($action != 'preview' && isset($form['actions'][$action]['#type']) && $form['actions'][$action]['#type'] === 'submit') {
        $form['actions'][$action]['#submit'][] = 'watchdog_watchdog_submit_handler';
      }
    }
  }

  /**
   * Helper function, allows plugins to alter "system_logging_settings" form.
   *
   * @param array $form
   *   The form object.
   */
  public function newFunctionalityFromPlugins(array &$form) {
    $this->wWatchdog->plugins()->formAlter($form);
  }

  /**
   * Submit handler for the "system_logging_settings" form.
   *
   * @param array $form
   *   The form object.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   */
  public function submit(array &$form, FormStateInterface $form_state) {
    $this->wWatchdog->plugins()->formSubmit($form, $form_state);
  }

  /**
   * Validation handler for the "system_logging_settings" form.
   *
   * @param array $form
   *   The form object.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   */
  public function validate(array &$form, FormStateInterface $form_state) {
    $this->wWatchdog->plugins()->formValidate($form, $form_state);
  }

}
