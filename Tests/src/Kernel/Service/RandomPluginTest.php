<?php
/**
 * @file
 *   Contains \Drupal\autovalue\Tests\Kernel\Service\AutoValuePluginManagerTest.
 */

namespace Drupal\autovalue\Tests\Kernel\Service;

use Drupal\autovalue\Plugin\AutoValueInterface;
use Drupal\Component\Plugin\ConfigurablePluginInterface;
use Drupal\KernelTests\KernelTestBase;
use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
use Drupal\user\Entity\User;
use Drupal\user\UserInterface;

/**
 * Tests the random AutoValue plugin.
 *
 * @group autovalue
 */
class RandomPluginTest extends KernelTestBase {

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
   * Tests that the plugin manager retrieves the token plugin.
   */
  public function testGetRandomPlugin() {
    /**
     * @var AutoValueInterface $token_plugin_instance
     */
    $token_plugin_instance = $this->plugin_manager->createInstance('random');
    $this->assertTrue($token_plugin_instance instanceof AutoValueInterface, 'Random plugin instance is of the AutoValueInterface class.');
    $this->assertTrue($token_plugin_instance instanceof ConfigurablePluginInterface, 'Random plugin instance is a configurable plugin.');
    $this->assertTrue(($token_plugin_instance->getPluginId() === 'random'), 'The plugin is the `random` plugin.');
  }

  /**
   * Tests that the default AutoValue plugin is accessible.
   */
  public function testRandomPluginValue() {

    /**
     * @var \Drupal\autovalue\Plugin\AutoValue\TokenPattern $random_plugin_instance
     */
    $random_plugin_instance = $this->plugin_manager->createInstance('random', [
      'value_type' => 'string',
      'length' => 32,
    ]);

    // Tests the random value isn't regenerated for each call value acquisition call.
    $this->assertEquals($random_plugin_instance->getValue(), $random_plugin_instance->getValue(), 'The random value is persistent.');
    // Tests the random value isn't regenerated for each call value acquisition call.
    $this->assertNotEquals($random_plugin_instance->getValue(), $random_plugin_instance->resetValue()->getValue(), 'The random value is persistent.');
  }
}
