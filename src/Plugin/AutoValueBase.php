<?php
/**
 * @file
 *   Contains \Drupal\autovalue\Plugin\AutoValue\AutoValueBase.
 */

namespace Drupal\autovalue\Plugin;

use Drupal\Core\Plugin\PluginBase;
use Drupal\Core\Plugin\PluginDependencyTrait;

abstract class AutoValueBase extends PluginBase implements AutoValueInterface {

  /**
   * Provides a calculateDependencies method that auto calculates dependencies
   *   for plugins.
   */
  use PluginDependencyTrait;


  /**
   * {@inheritdoc}
   */
  public function getLabel() {
    return $this->pluginDefinition['label'];
  }

  /**
   * {@inheritdoc}
   */
  public function calculateDependencies() {
    // Calculate plugin dependencies for self.
    $this->calculatePluginDependencies($this);

    return $this->dependencies;
  }

}
