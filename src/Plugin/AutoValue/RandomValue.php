<?php
/**
 * @file
 *   Contains Drupal\autovalue\Plugin\AutoValue\RandomValue.
 */

namespace Drupal\autovalue\Plugin\AutoValue;

use Drupal\autovalue\Plugin\AutoValueInterface;
use Drupal\autovalue\Plugin\ConfigurableAutoValueBase;
use Drupal\Component\Utility\Random;

/**
 * Defines an random value generator.
 *
 * @AutoValue(
 *   id = "random",
 *   label = @Translation("Random Value"),
 *   description = @Translation("Generates a random value."),
 * )
 */
class RandomValue extends ConfigurableAutoValueBase implements AutoValueInterface {

  /**
   * The generated value.
   *
   * @var mixed
   */
  protected $generated_value = NULL;

  /**
   * {@inheritdoc}
  */
  public function defaultConfiguration() {
    return [
      'value_type' => 'string',
      'length' => 8,
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function getValue() {
    // Just get the value if it has already been generated.
    if(!is_null($this->generated_value)) {
      return $this->generated_value;
    }

    $generator = new Random();
    // Gets the generator callback.
    $callable = [$generator, $this->configuration['value_type']];

    $parameters = array_diff_key(['value_type'=>1], $this->configuration);

    if (is_callable($callable)) {
      $this->generated_value = call_user_func_array($callable, $parameters);
    }
    return $this->generated_value;
  }

  /**
   * {@inheritdoc}
   */
  public function getSummary() {
    return [
      'type' => ['#markup' => $this->getLabel()],
    ];
  }

  /**
   * Reset the random value so it will be regenerated.
   *
   * @return $this
   */
  public function resetValue() {
    $this->generated_value = NULL;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getSettingsForm(array $form, array &$form_state) {
    return parent::getSettingsForm($form, $form_state) + [
      'value_type' => [
        '#type' => 'select',
        '#title' => $this->t('Type of value.'),
        '#description' => $this->t('Select the type of value to be generated.'),
        '#options' => [
          '--select--' => 'empty',
          'string' => $this->t('String'),
        ],
        '#default_value' => $this->configuration['value_type'],
      ],
      'length' => [
        '#type' => 'number',
        '#title' => $this->t('Length.'),
        '#description' => $this->t('Select the length the generated value should be.'),
        '#default_value' => $this->configuration['length'],
      ],
    ];
  }
}
