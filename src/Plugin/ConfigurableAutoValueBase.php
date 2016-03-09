<?php
/**
 * @file
 *   Contains \Drupal\autovalue\Plugin\AutoValue\ConfigurableAutoValueBase.
 */

namespace Drupal\autovalue\Plugin;

use Drupal\Component\Plugin\ConfigurablePluginInterface;
use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Plugin\PluginDependencyTrait;

abstract class ConfigurableAutoValueBase extends AutoValueBase implements ConfigurablePluginInterface {

  /**
   * {@inheritdoc}
   */
  public function getSettingsForm(array $form, array &$form_state) {

    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [];
  }


  /**
   * {@inheritdoc}
   */
  public function getConfiguration() {
    return $this->configuration;
  }

  /**
   * {@inheritdoc}
   */
  public function setConfiguration(array $configuration) {
    $this->configuration = NestedArray::mergeDeep(
      $this->defaultConfiguration(),
      $configuration
    );

    return $this;
  }
}
