<?php
/**
 * @file
 *   Contains \Drupal\autovalue\Plugin\AutoValue\TokenPattern.
 */

namespace Drupal\autovalue\Plugin\AutoValue;

use Drupal\autovalue\Plugin\AutoValueInterface;
use Drupal\autovalue\Plugin\ConfigurableAutoValueBase;
use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Utility\Token;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines an automatic value plugin which is based on text patterns with
 * tokens.
 *
 * @AutoValue(
 *   id = "token",
 *   label = @Translation("Token Pattern"),
 *   description = @Translation("Generates a values based on a text pattern with tokens."),
 *   default_destination_id = "title",
 *   default_pattern = @Translation("%entity_type-%bundle_type-%enitty_id")
 * )
 */
class TokenPattern extends ConfigurableAutoValueBase implements AutoValueInterface, ContainerFactoryPluginInterface {

  /**
   * The token data to pass on to token->replace().
   *
   * @var mixed
   */
  protected $token_data;

  /**
   * The token options to pass on to token->replace().
   *
   * @var mixed
   */
  protected $token_options;

  /**
   * The token service.
   *
   * @var \Drupal\Core\Utility\Token
   */
  private $token;

  /**
   * Constructs a Drupal\Component\Plugin\PluginBase object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, Token $token) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->token = $token;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('token')
    );
  }

  /**
   * Returns the token pattern.
   *
   * @return string
   */
  public function getPattern() {
    return $this->configuration['pattern'];
  }

  /**
   * {@inheritdoc}
   */
  public function getValue() {
    $token_data = $this->getTokenData();
    $token_options = $this->getTokeOptions();
    return $this->token
      ->replace($this->getPattern(), $token_data ?: [], $token_options ?: []);
  }

  /**
   * {@inheritdoc}
   */
  public function getSettingsForm(array $form, array &$form_state) {

    return parent::getSettingsForm($form, $form_state) + [
      'pattern' => [
        '#type' => 'textarea',
        '#title' => $this->t('Token Pattern'),
        '#rows' => 2,
        '#default_value' => $this->getPattern(),
        '#description' => $this->t('Enter a pattern to use for generating a value.'),
        '#required' => TRUE,
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getSummary() {
    return array(
      '#markup' => $this->t('Pattern: %pattern', array('%pattern' => $this->getPattern())),
    );
  }

  /**
   * {@inheritdoc}
   *
   * @todo remove test pattern.
   */
  public function defaultConfiguration() {
    return [
      'pattern' => '',
    ] + parent::defaultConfiguration();
  }

  /**
   * Gets the token data. This is often an entity but could be other relevant
   * data. @see \Drupal\Core\Utility\Token::replace().
   *
   * @return mixed
   *   The token data.
   */
  public function getTokenData() {
    return $this->token_data;
  }

  /**
   * Sets the data to be used with token->replace().
   *
   * @param $token_data
   *   The token data value.
   * @return $this
   *   This plugin.
   */
  public function setTokenData($token_data) {
    $this->token_data = $token_data;
    return $this;
  }

  /**
   * Gets the token options. @see \Drupal\Core\Utility\Token::replace().
   *
   * @return mixed
   *   The token data.
   */
  public function getTokeOptions() {
    return $this->token_options;
  }

  /**
   * Sets the options to be used with token->replace().
   *
   * @param $token_data
   *   The token data value.
   * @return $this
   *   This plugin.
   */
  public function setTokenOptions($token_options) {
    $this->token_options = $token_options;
    return $this;
  }
}
