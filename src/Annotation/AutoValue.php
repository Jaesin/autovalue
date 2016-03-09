<?php
/**
 * @file
 *   Contains \Drupal\autovalue\Annotation\AutoValue.
 */

namespace Drupal\autovalue\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines an AutoValue annotation object.
 *
 * @ingroup autovalue
 *
 * @Annotation
 */
class AutoValue extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The administrative label of the plugin instance.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label = '';

  /**
   * A descriptive summary of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $description = '';

  /**
   * The default destination property id.
   *
   * @var string
   */
  public $default_destination_id = '';

  /**
   * The default pattern to use for value generation.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $default_pattern = '';

}
