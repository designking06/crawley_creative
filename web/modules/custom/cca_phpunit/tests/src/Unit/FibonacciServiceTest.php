<?php

namespace Drupal\Tests\cca_phpunit\Unit;

use Drupal\cca_phpunit\FibonacciService;
use Drupal\Tests\UnitTestCase;

/**
 * Main test class for Fibonacci Service.
 */
class FibonacciServiceTest extends UnitTestCase {

  public function testSixthFibonacciNumber() {
    $fibonacciService = new FibonacciService();
    $fibonacciSequence = $fibonacciService->calcSomeFibos(6);
    $this->assertEquals(
      $fibonacciSequence[5],
      5 // Fails.
    );

    $this->assertEquals(
      $fibonacciSequence[4],
      3 // Pass.
    );
  }

}
