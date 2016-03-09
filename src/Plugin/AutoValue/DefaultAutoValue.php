<?php
/**
 * @file
 *   Contains \Drupal\autovalue\Plugin\AutoValue\DefaultAutoValue.
 */

namespace Drupal\autovalue\Plugin\AutoValue;

use Drupal\autovalue\Plugin\AutoValueBase;
use Drupal\autovalue\Plugin\AutoValueInterface;

/**
 * Defines an automatic value plugin which does nothing.
 *
 * @AutoValue(
 *   id = "default",
 *   label = @Translation("No automatic value"),
 *   description = @Translation("Provides an empty value."),
 *   default_pattern = @Translation("")
 * )
 */
class DefaultAutoValue extends AutoValueBase {

  /**
   * {@inheritdoc}
   */
  public function getValue() {
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getSummary() {
    return [
      'label' => [
        '#markup' => $this->t('Type: %label', ['%label' => $this->getLabel()]),
      ],
    ];
  }
}
