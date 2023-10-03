<?php

/**
 * @file
 * Generate markup to be displayed
 */

namespace Drupal\cca_module\Controller;

use Drupal\Core\Controller\ControllerBase;

class FirstController extends ControllerBase {

  public function simpleContent() {
    return [
      '#type' => 'markup',
      '#markup' => t('Hello Drupal.'),
    ];
  }

  public function variableContent($name_1 = 'Caleb', $name_2 = 'Jonae') {
    return [
      '#type' => 'markup',
      '#markup' => t('hello @name1 and @name2',
        ['@name1' => $name_1, '@name2' => $name_2]),
    ];
  }
}
