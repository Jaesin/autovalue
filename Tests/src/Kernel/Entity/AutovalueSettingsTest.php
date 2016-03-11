<?php

/**
 * @file
 * Contains \Drupal\Tests\autovalue\Kernel\AutovalueSettingsTest.
 */

namespace Drupal\Tests\autovalue\Kernel;

use Drupal\autovalue\Entity\AutovalueSettings;
use Drupal\KernelTests\KernelTestBase;

/**
 * @coversDefaultClass \Drupal\autovalue\Entity\AutovalueSettings
 * @group autovalue
 */
class AutovalueSettingsTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = ['autovalue'];

  /**
   * @covers ::loadByEntityTypeAndBundle
   */
  public function testLoadByEntityTypeAndBundle() {
    $setting = AutovalueSettings::loadByEntityTypeAndBundle('example', 'bundle');
    $this->assertInstanceOf(AutovalueSettings::class, $setting);
    $this->assertEquals('example.bundle', $setting->id());
    $this->assertEquals('example', $setting->getTargetEntityTypeId());
    $this->assertEquals('bundle', $setting->getTargetBundle());

    $setting->save();
  }

  public function testConfiguration() {
    $setting = AutovalueSettings::loadByEntityTypeAndBundle('example', 'bundle');

    $setting->getConfiguration()->addInstanceId('my_field', [
      'id' => 'token',
      'pattern' => 'hello-world'
    ]);
    $setting->save();

    $result = $setting->toArray();
    unset($result['uuid']);
    $this->assertEquals([
      'id' => 'example.bundle',
      'langcode' => 'en',
      'status' => TRUE,
      'dependencies' => [],
      'target_entity_type_id' => 'example',
      'target_bundle' => 'bundle',
      'configuration' => [
        'my_field' => [
          'id' => 'token',
          'pattern' => 'hello-world',
        ],
      ],
    ], $result);
  }

}
