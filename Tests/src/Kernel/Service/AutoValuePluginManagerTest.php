<?php
/**
 * @file
 *   Contains \Drupal\autovalue\Tests\Kernel\Service\AutoValuePluginManagerTest.
 */

namespace Drupal\autovalue\Tests\Kernel\Service;

use Drupal\autovalue\Plugin\AutoValueInterface;
use Drupal\autovalue\Service\AutoValuePluginManagerInterface;
use Drupal\KernelTests\KernelTestBase;

/**
 * Tests the AutoValuePluginManager and the default plugin.
 *
 * @group autovalue
 */
class AutoValuePluginManagerTest extends KernelTestBase {

  /**
   * The selection handler.
   *
   * @var \Drupal\autovalue\Service\AutoValuePluginManagerInterface.
   */
  protected $plugin_manager;

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['autovalue', 'system'];

  protected function setUp() {
    parent::setUp();

    $this->plugin_manager = \Drupal::service('plugin.manager.autovalue');
  }

  /**
   * Tests that the default AutoValue plugin manager is accessible.
   */
  public function testGetPluginmanager() {
    $this->assertTrue($this->plugin_manager instanceof AutoValuePluginManagerInterface, 'Auto Value plugin manager is instantiated.');
  }

  /**
   * Tests that the plugin manager can get plugin definitions.
   */
  public function testGetPlugins() {
    $plugin_definitions = $this->plugin_manager->getDefinitions();
    $this->assertTrue(is_array($plugin_definitions), "Get plugin definitions.");
    $this->assertArraySubset([
      'default' => [
        'id'=>'default',
        'default_field_id' => '',
      ]], $plugin_definitions, 'The default plugin definition exists.');
    $this->assertArraySubset([
      'token' => [
        'id'=>'token',
        'default_field_id'=>'title',
      ]], $plugin_definitions, 'The token plugin definition exists.');
  }

  /**
   * Tests that the default AutoValue plugin is accessible.
   */
  public function testDefaultPlugin() {
    /**
     * @var AutoValueInterface $default_plugin_instance
     */
    $default_plugin_instance = $this->plugin_manager->createInstance('default');
    $this->assertTrue($default_plugin_instance instanceof AutoValueInterface, 'Default plugin instance is of the AutoValueInterface class.');
    $this->assertTrue(($default_plugin_instance->getPluginId() === 'default'), 'Default is the `defult` plugin.');

    // Make sure the default plugin returns a null value.
    $this->assertNull($default_plugin_instance->getValue(), 'The default plugin does not return a value.');
  }
}
