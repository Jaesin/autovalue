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
 * Tests the toek AutoValue plugin.
 *
 * @group autovalue
 */
class TokenPluginTest extends KernelTestBase {

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
  public static $modules = ['autovalue', 'system', 'node', 'user'];

  protected function setUp() {
    parent::setUp();

    $this->plugin_manager = \Drupal::service('plugin.manager.autovalue');
  }

  /**
   * Tests that the plugin manager retrieves the token plugin.
   */
  public function testGetTokenPlugin() {
    /**
     * @var AutoValueInterface $token_plugin_instance
     */
    $token_plugin_instance = $this->plugin_manager->createInstance('token');
    $this->assertTrue($token_plugin_instance instanceof AutoValueInterface, 'Token plugin instance is of the AutoValueInterface class.');
    $this->assertTrue($token_plugin_instance instanceof ConfigurablePluginInterface, 'Token plugin instance is a configurable plugin.');
    $this->assertTrue(($token_plugin_instance->getPluginId() === 'token'), 'The plugin is the `token` plugin.');
  }

  /**
   * Tests that the default AutoValue plugin is accessible.
   */
  public function testTokenPluginValue() {

    $node_data = [
      'type' => 'article',
      'title' => 'Not the greatest title. Just a tribute.',
    ];

    $node = Node::create($node_data);

    $user_data = [
      'name' => 'Test User',
      'mail' => 'foo@example.com',
    ];

    $user = User::create($user_data);

    $this->assertTrue($node instanceof NodeInterface, '$node is a node.');
    $this->assertTrue($user instanceof UserInterface, '$user is a user');

    /**
     * @var \Drupal\autovalue\Plugin\AutoValue\TokenPattern $token_plugin_instance
     */
    $token_plugin_instance = $this->plugin_manager->createInstance('token', [
      'pattern' => '',
    ]);

    // Tests an empty token value;
    $this->assertEquals('', $token_plugin_instance->setTokenData([
      'node' => $node,
      'user' => $user,
    ])->getValue(), 'Make sure an empty pattern returns an empty string.');

    $token_plugin_instance->setConfiguration([
      'pattern' => '[node:title]',
    ]);

    // Tests the token plugin gets the node title.
    $this->assertEquals($node_data['title'], $token_plugin_instance->getValue());

    $token_plugin_instance->setConfiguration([
      'pattern' => '[user:name] - [user:mail]',
    ]);

    // Tests the token plugin for users.
    $this->assertEquals("${user_data['name']} - ${user_data['mail']}", $token_plugin_instance->getValue());
  }
}
