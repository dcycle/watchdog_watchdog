<?php

namespace Drupal\watchdog_watchdog\WWatchdogPlugin;

use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\watchdog_watchdog\WWatchdogEvent\WWatchdogEventInterface;

/**
 * A base class to help developers implement WWatchdogPlugin objects.
 *
 * @see \Drupal\watchdog_watchdog\Annotation\WWatchdogPluginAnnotation
 * @see \Drupal\watchdog_watchdog\WWatchdogPlugin\WWatchdogPluginInterface
 */
abstract class WWatchdogPluginBase extends PluginBase implements WWatchdogPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function alter(WWatchdogEventInterface $info) {}

  /**
   * {@inheritdoc}
   */
  public function alterEvent(WWatchdogEventInterface $event) {}

  /**
   * {@inheritdoc}
   */
  public function formAlter(array &$form) {}

  /**
   * {@inheritdoc}
   */
  public function formSubmit(array $form, FormStateInterface $form_state) {}

  /**
   * {@inheritdoc}
   */
  public function formValidate(array $form, FormStateInterface $form_state) {}

  /**
   * {@inheritdoc}
   */
  public function triggersError(WWatchdogEventInterface $event, bool &$opinion) {}

  /**
   * {@inheritdoc}
   */
  public function trips(WWatchdogEventInterface $event, bool &$opinion) {}

}
