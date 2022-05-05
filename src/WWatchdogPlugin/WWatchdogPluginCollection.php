<?php

namespace Drupal\watchdog_watchdog\WWatchdogPlugin;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Utility\Error;
use Drupal\watchdog_watchdog\WWatchdogEvent\WWatchdogEventInterface;

/**
 * Abstraction around a collection of plugins.
 */
class WWatchdogPluginCollection implements WWatchdogPluginInterface, \Countable {

  use StringTranslationTrait;

  /**
   * The injected messenger.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * The injected WWatchdogPluginManager.
   *
   * @var \Drupal\watchdog_watchdog\WWatchdogPlugin\WWatchdogPluginManager
   */
  protected $wWatchdogPluginManager;

  /**
   * Constructs a new WWatchdogPluginCollection object.
   *
   * @param \Drupal\watchdog_watchdog\WWatchdogPlugin\WWatchdogPluginManager $wWatchdogPluginManager
   *   An injected WWatchdogPluginManager service.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   An injected messenger.
   */
  public function __construct(WWatchdogPluginManager $wWatchdogPluginManager, MessengerInterface $messenger) {
    $this->wWatchdogPluginManager = $wWatchdogPluginManager;
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public function alter(WWatchdogEventInterface $event) {
    $this->callOnPlugins('alter', [$event]);
  }

  /**
   * {@inheritdoc}
   */
  public function alterEvent(WWatchdogEventInterface $event) {
    $this->callOnPlugins('alterEvent', [$event]);
  }

  /**
   * {@inheritdoc}
   */
  public function count() {
    return count($this->plugins());
  }

  /**
   * {@inheritdoc}
   */
  public function formAlter(array &$form) {
    $this->callOnPlugins('formAlter', [$form]);
  }

  /**
   * {@inheritdoc}
   */
  public function formSubmit(array $form, FormStateInterface $form_state) {
    $this->callOnPlugins('formSubmit', [$form, $form_state]);
  }

  /**
   * {@inheritdoc}
   */
  public function formValidate(array $form, FormStateInterface $form_state) {
    $this->callOnPlugins('formValidate', [$form, $form_state]);
  }

  /**
   * Call a method on all plugins, display errors if exceptions occur.
   *
   * @param string $method
   *   The method to call.
   * @param array $arguments
   *   Arguments to pass, for example [&$info].
   */
  protected function callOnPlugins(string $method, array $arguments = []) {
    foreach ($this->plugins() as $plugin) {
      try {
        call_user_func_array([$plugin, $method], $arguments);
      }
      catch (\Throwable $t) {
        $this->displayErrorToUser($t);
      }
    }
  }

  /**
   * Display a \Throwable to the user.
   *
   * @param \Throwable $throwable
   *   A \Throwable.
   */
  public function displayErrorToUser(\Throwable $throwable) {
    $this->messenger->addError($this->t('%type: @message in %function (line %line of %file).', Error::decodeException($throwable)));
  }

  /**
   * Mockable wrapper for \Drupal::service('plugin.manager.watchdog_watchdog').
   *
   * @return \Drupal\watchdog_watchdog\WWatchdogPlugin\WWatchdogPluginManager
   *   The WWatchdogPluginManager service. We are not specifying its type
   *   here because during testing we want to mock pluginManager() without
   *   extending WWatchdogPluginManager; when we do, it works fine in
   *   PHPUnit directly. However when attempting to run within Drupal we
   *   get an unhelpful message as described in
   *   https://drupal.stackexchange.com/questions/252930. Therefore we simply
   *   use an anonymous class.
   *
   * @throws \Exception
   */
  public function pluginManager() {
    return $this->wWatchdogPluginManager;
  }

  /**
   * Get plugin objects.
   *
   * @param bool $reset
   *   Whether to re-fetch plugins; otherwise we use the static variable.
   *   This can be useful during testing.
   *
   * @return array
   *   Array of plugin objects.
   *
   * @throws \Exception
   */
  public function plugins(bool $reset = FALSE) : array {
    static $return = NULL;

    if ($return === NULL || $reset) {
      $return = [];
      foreach (array_keys($this->pluginDefinitions()) as $plugin_id) {
        $return[$plugin_id] = $this->pluginManager()->createInstance($plugin_id, ['of' => 'configuration values']);
      }
    }

    return $return;
  }

  /**
   * Get plugin definitions based on their annotations.
   *
   * @return array
   *   Array of plugin definitions.
   *
   * @throws \Exception
   */
  public function pluginDefinitions() : array {
    $return = $this->pluginManager()->getDefinitions();

    uasort($return, function (array $a, array $b) : int {
      if ($a['weight'] == $b['weight']) {
        return 0;
      }
      return ($a['weight'] < $b['weight']) ? -1 : 1;
    });

    return $return;
  }

  /**
   * {@inheritdoc}
   */
  public function triggersError(WWatchdogEventInterface $event, bool &$opinion) {
    $this->callOnPlugins('triggersError', [$event, &$opinion]);
  }

}
