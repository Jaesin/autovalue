<?php
/**
 * @file:
 *   Contains \Drupal\autovalue\Plugin\AutoValueInterface
 */

namespace Drupal\autovalue\Plugin;

use Drupal\Component\Plugin\PluginInspectionInterface;

/**
 * Provides an interface for autovalue plugins.
 *
 * @todo: Consider using context aware plugin interface or configurable.
 *   @see \Drupal\Component\Plugin\ContextAwarePluginInterface
 *   @see \Drupal\Component\Plugin\ConfigurablePluginInterface
 *
 * @package Drupal\autovalue\Plugin
 */
interface AutoValueInterface extends PluginInspectionInterface {

  /**
   * Gets the processed value.
   *
   * @return mixed
   *   The value used to set the destination property.
   */
  public function getValue();

  /**
   * Returns a render array summarizing the configuration of the automatic value
   * plugin.
   *
   * @return array
   *   A render array.
   */
  public function getSummary();

  /**
   * Returns a label for the AutoValue plugin, for admin listings.
   *
   * @return string
   *   The default value plugin label.
   */
  public function getLabel();

}
