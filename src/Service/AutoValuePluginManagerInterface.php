<?php
/**
 * @file:
 *   Contains \Drupal\autovalue\Service\AutoValuePluginManagerInterface.
 */

namespace Drupal\autovalue\Service;


use Drupal\Component\Plugin\Discovery\CachedDiscoveryInterface;
use Drupal\Component\Plugin\PluginManagerInterface;

interface AutoValuePluginManagerInterface extends PluginManagerInterface, CachedDiscoveryInterface {

}
