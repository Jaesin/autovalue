<?php

/**
 * @file
 * Contains \Drupal\autovalue\Element\AutovalueConfiguration.
 */

namespace Drupal\autovalue\Element;

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\PluginFormInterface;
use Drupal\Core\Render\Element\FormElement;

/**
 * Provides autovalue element configuration.
 *
 * @FormElement("autovalue_configuration")
 */
class AutovalueConfiguration extends FormElement {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    $class = get_class($this);
    return [
      '#input' => TRUE,
      '#tree' => TRUE,
      '#process' => [
        [$class, 'processAutovalueConfiguration'],
      ],
    ];
  }

  /**
   * Process handler for the autovalue_configuration form element.
   */
  public static function processAutovalueConfiguration(&$element, FormStateInterface $form_state, &$form) {
    /** @var \Drupal\autovalue\Entity\AutovalueSettings $autovalue_settings */
    $autovalue_settings = $element['#default_value'];

    /** @var \Drupal\autovalue\Service\AutoValuePluginManagerInterface $autovalue_manager */
    $autovalue_manager = \Drupal::service('plugin.manager.autovalue');
    $options = array_map(function ($item) {
      return $item['label'];
    }, $autovalue_manager->getDefinitions());

    /** @var \Drupal\Core\Entity\EntityFieldManagerInterface $field_manager */
    $field_manager = \Drupal::service('entity_field.manager');
    $target_entity_type_id = $autovalue_settings->getTargetEntityTypeId();
    $target_bundle = $autovalue_settings->getTargetBundle();
    $fields = $field_manager->getFieldDefinitions($target_entity_type_id, $target_bundle);

    $element['#tree'] = TRUE;
    foreach ($fields as $field_name => $field_definition) {
      $element[$field_name]['#markup'] = $field_definition->getLabel();

      $element[$field_name]['id'] = [
        '#type' => 'select',
        '#title' => t('Autovalue type'),
        '#options' => $options,
        '#ajax' => [
          'callback' => '\Drupal\autovalue\Element\AutovalueConfiguration::updatePluginConfiguration',
          'wrapper' => "autovalue-settings-$target_entity_type_id-$target_bundle-$field_name-configuration",
        ],
      ];

      $plugin_id = isset($form_state->getUserInput()['settings'][$target_entity_type_id][$target_bundle]['autovalue_configuration'][$field_name]['id']) ? $form_state->getUserInput()['settings'][$target_entity_type_id][$target_bundle]['autovalue_configuration'][$field_name]['id'] : 'default';
      // @todo configuration
      $plugin = $autovalue_manager->createInstance($plugin_id, []);
      $element[$field_name]['configuration'] = [
        '#prefix' => '<div id="' . "autovalue-settings-$target_entity_type_id-$target_bundle-$field_name-configuration" . '>',
        '#suffix' => '</div>',
      ];
      if ($plugin instanceof PluginFormInterface) {
        $element[$field_name]['configuration'] += $plugin->buildConfigurationForm($element[$field_name]['configuration'], $form_state);
      }
    }

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public static function updatePluginConfiguration($form, FormStateInterface $form_state) {
    $array_parents = $form_state->getTriggeringElement()['#array_parents'];
    array_pop($array_parents);
    $array_parents[] = 'configuration';
    return NestedArray::getValue($form, $array_parents);
  }

}
