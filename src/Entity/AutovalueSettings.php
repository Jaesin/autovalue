<?php

/**
 * @file
 * Contains \Drupal\autovalue\Entity\AutovalueSettings.
 */

namespace Drupal\autovalue\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\Core\Plugin\DefaultLazyPluginCollection;

/**
 * Defines the autovalue settings entity.
 *
 * @ConfigEntityType(
 *   id = "autovalue_settings",
 *   label = @Translation("Autovalue Settings"),
 *   admin_permission = "administer site configuration",
 *   config_prefix = "autovalue_settings",
 *   entity_keys = {
 *     "id" = "id"
 *   },
 *   config_export = {
 *     "id",
 *     "target_entity_type_id",
 *     "target_bundle",
 *     "configuration",
 *   }
 * )
 */
class AutovalueSettings extends ConfigEntityBase {

  /**
   * The ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The targeted entity type ID.
   *
   * @var string
   */
  protected $target_entity_type_id = '';

  /**
   * The target bundle.
   *
   * @var string
   */
  protected $target_bundle;

  /**
   * @var \Drupal\Core\Plugin\DefaultLazyPluginCollection
   */
  protected $configuration;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $values, $entity_type) {
    parent::__construct($values, $entity_type);

    $values += [
      'configuration' => [],
    ];

    $this->configuration = new DefaultLazyPluginCollection(\Drupal::service('plugin.manager.autovalue'), $values['configuration']);
  }

  /**
   * {@inheritdoc}
   */
  public function toArray() {
    $data = parent::toArray();

    $data['configuration'] = $data['configuration']->getConfiguration();
    return $data;
  }

  /**
   * Returns the plugin configuration.
   *
   * @return \Drupal\Core\Plugin\DefaultLazyPluginCollection
   */
  public function getConfiguration() {
    return $this->configuration;
  }

  /**
   * Loads the autovalue settings by entity type.
   *
   * @param string $entity_type_id
   *   The entity type ID.
   *
   * @return static
   */
  public static function loadByEntityTypeAndBundle($entity_type_id, $bundle) {
    $config = \Drupal::entityTypeManager()->getStorage('autovalue_settings')->load($entity_type_id . '.' . $bundle);
    if ($config == NULL) {
      $config = AutovalueSettings::create(['id' => $entity_type_id . '.' . $bundle, 'target_entity_type_id' => $entity_type_id, 'target_bundle' => $bundle]);
    }
    return $config;
  }

  /**
   * @return string
   */
  public function getTargetEntityTypeId() {
    return $this->target_entity_type_id;
  }

  /**
   * @return string
   */
  public function getTargetBundle() {
    return $this->target_bundle;
  }

}
