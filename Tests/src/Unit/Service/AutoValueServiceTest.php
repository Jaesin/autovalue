<?php
/**
 * @file:
 *   Contains Drupal\autovalue\Tests\Unit\Service\AutoValueServiceTest
 */

namespace Drupal\autovalue\Tests\Unit\Service;

use Drupal\Tests\UnitTestCase;

/**
 * @coversDefaultClass \Drupal\autovalue\Service\AutoValueService
 * @group autovalue
 */
class AutoValueServiceTest extends UnitTestCase {

  /**
   * The autovalue module service.
   *
   * @var \Drupal\autovalue\Service\AutoValueServiceInterface
   */
  protected $autovalue_service;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->autovalue_service = $this->getMock('Drupal\autovalue\Service\AutoValueService');
  }

  /**
   * Make sure the AutoValueService service implements the appropriate interface..
   */
  public function testAutoValueServiceInterface() {
    $this->assertTrue($this->autovalue_service instanceof \Drupal\autovalue\Service\AutoValueServiceInterface);
  }
}
