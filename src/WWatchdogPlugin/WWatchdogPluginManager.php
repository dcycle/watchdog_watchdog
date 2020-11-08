<?php

namespace Drupal\watchdog_watchdog\WWatchdogPlugin;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\watchdog_watchdog\Annotation\WWatchdogPluginAnnotation;

/**
 * The plugin manager.
 */
// See https://github.com/mglaman/phpstan-drupal/issues/113
// @codingStandardsIgnoreStart
// @phpstan-ignore-next-line
class WWatchdogPluginManager extends DefaultPluginManager {
// @codingStandardsIgnoreEnd

  /**
   * Creates the discovery object.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  // See https://github.com/mglaman/phpstan-drupal/issues/112
  // @codingStandardsIgnoreStart
  // @phpstan-ignore-next-line
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
  // @codingStandardsIgnoreEnd
    // We replace the $subdir parameter with our own value.
    // This tells the plugin manager to look for plugins in the
    // 'src/Plugin/WWatchdogPlugin' subdirectory of any enabled modules.
    // This also serves to define the PSR-4 subnamespace in which plugins will
    // live. Modules can put a plugin class in their own namespace such as
    // Drupal\{module_name}\Plugin\WWatchdogPlugin\MyPlugin.
    $subdir = 'Plugin/WWatchdogPlugin';

    // The name of the interface that plugins should adhere to. Drupal will
    // enforce this as a requirement. If a plugin does not implement this
    // interface, Drupal will throw an error.
    $plugin_interface = WWatchdogPluginInterface::class;

    // The name of the annotation class that contains the plugin definition.
    $plugin_definition_annotation_name = WWatchdogPluginAnnotation::class;

    parent::__construct($subdir, $namespaces, $module_handler, $plugin_interface, $plugin_definition_annotation_name);
  }

}
