<?php

namespace Drupal\watchdog_watchdog\WWatchdogPlugin;

use Drupal\Core\Form\FormStateInterface;
use Drupal\watchdog_watchdog\WWatchdogEvent\WWatchdogEventInterface;

/**
 * An interface for all WWatchdogPlugin type plugins.
 */
interface WWatchdogPluginInterface {

  /**
   * Alter the event.
   *
   * @param \Drupal\watchdog_watchdog\WWatchdogEvent\WWatchdogEventInterface $event
   *   The event.
   */
  public function alterEvent(WWatchdogEventInterface $event);

  /**
   * Alter the logging admin form.
   */
  public function formAlter(array &$form);

  /**
   * React to submission of the logging admin form.
   *
   * @param array $form
   *   The form object.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   */
  public function formSubmit(array $form, FormStateInterface $form_state);

  /**
   * Run validation on the logging admin form.
   *
   * @param array $form
   *   The form object.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   */
  public function formValidate(array $form, FormStateInterface $form_state);

  /**
   * Check whether an event triggers an error.
   *
   * @param \Drupal\watchdog_watchdog\WWatchdogEvent\WWatchdogEventInterface $event
   *   The event.
   * @param bool $opinion
   *   An opinion on whether this should trigger an error. It can be modified
   *   by plugins.
   */
  public function triggersError(WWatchdogEventInterface $event, bool &$opinion);

}
