<?php

namespace Drupal\client_admin\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountInterface;

/*
 * Provides route response for the CCAdmin Home Page.
 */
class HomePageController extends ControllerBase {

  /**
   * Contruct the CCAdmin HomePageController.
   *
   * @param AccountInterface $current_user
   *   The current user.
   */
  public function __construct(AccountInterface $current_user)
  {
    $this->currentUser = $current_user;
  }

  /**
   * Returns home page content.
   *
   * @return array
   *   A render array.
   */
  public function content() {
    $user = $this->entityTypeManager->getStorage('user')->load($this->currentUser->id());

    return [
      // Custom theme hook name
      '#theme' => 'client_admin_theme__landing_page',
    ];
  }
}
