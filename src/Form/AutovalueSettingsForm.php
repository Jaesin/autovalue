<?php

/**
 * @file
 * Contains \Drupal\autovalue\Form\AutovalueSettingsForm.
 */

namespace Drupal\autovalue\Form;

use Drupal\autovalue\Entity\AutovalueSettings;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AutovalueSettingsForm extends FormBase {

  /**
   * The entity bundle info.
   *
   * @var \Drupal\Core\Entity\EntityTypeBundleInfoInterface
   */
  protected $entityBundleInfo;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a ContentLanguageSettingsForm object.
   *
   * @param \Drupal\Core\Entity\EntityTypeBundleInfoInterface $entity_bundle_info
   *   The entity bundle info.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeBundleInfoInterface $entity_bundle_info, EntityTypeManagerInterface $entity_type_manager) {
    $this->entityBundleInfo = $entity_bundle_info;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.bundle.info'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'autovalue_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $entity_types = $this->entityTypeManager->getDefinitions();
    $labels = [];
    $default = [];

    $bundles = $this->entityBundleInfo->getAllBundleInfo();
    $autovalue_configuration = [];
    foreach ($entity_types as $entity_type_id => $entity_type) {
      if (!$entity_type->isSubclassOf(FieldableEntityInterface::class)) {
        continue;
      }

      $labels[$entity_type_id] = $entity_type->getLabel() ?: $entity_type_id;
      $default[$entity_type_id] = FALSE;

      // Check whether we have any custom setting.
      foreach ($bundles[$entity_type_id] as $bundle => $bundle_info) {
        $config = AutovalueSettings::loadByEntityTypeAndBundle($entity_type_id, $bundle);
        $autovalue_configuration[$entity_type_id][$bundle] = $config;
      }
    }

    asort($labels);

    $form['entity_types'] = [
      '#title' => $this->t('Autovalue settings'),
      '#type' => 'checkboxes',
      '#options' => $labels,
      '#default_value' => $default,
    ];

    $form['settings'] = ['#tree' => TRUE];

    foreach ($labels as $entity_type_id => $label) {
      $entity_type = $entity_types[$entity_type_id];

      $form['settings'][$entity_type_id] = [
        '#title' => $label,
        '#type' => 'container',
        '#entity_type' => $entity_type_id,
        '#bundle_label' => $entity_type->getBundleLabel() ?: $label,
        '#states' => [
          'visible' => [
            ':input[name="entity_types[' . $entity_type_id . ']"]' => ['checked' => TRUE],
          ],
        ],
      ];

      foreach ($bundles[$entity_type_id] as $bundle => $bundle_info) {
        $form['settings'][$entity_type_id][$bundle] = [
          '#type' => 'item',
          '#label' => $bundle_info['label'],
          'autovalue_configuration' => [
            '#type' => 'autovalue_configuration',
            '#entity_information' => [
              'entity_type' => $entity_type_id,
              'bundle' => $bundle,
            ],
            '#default_value' => $autovalue_configuration[$entity_type_id][$bundle],
          ],
        ];
      }
    }

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save configuration'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    foreach ($form_state->getValue('settings') as $entity_type => $entity_settings) {
      foreach ($entity_settings as $bundle => $bundle_settings) {
//        $config = ContentLanguageSettings::loadByEntityTypeBundle($entity_type, $bundle);
//        $config->setDefaultLangcode($bundle_settings['settings']['language']['langcode'])
//          ->setLanguageAlterable($bundle_settings['settings']['language']['language_alterable'])
//          ->save();
      }
    }
    drupal_set_message($this->t('Settings successfully updated.'));
  }

}
