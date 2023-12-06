<?php

namespace Drupal\client_admin\Plugin\Block;

use Drupal\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Http\RequestStack;


/**
 * Provides a "Client Companies' Clients" Block.
 *
 * @Block(
 *   id = "client_company_clients",
 *   admin_label = @Translation("Company Clients block"),
 *   category = @Translation("Company Clients World"),
 * )
 */
class ClientCompanyClientsBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $results = $this->grabCompanyClients();

    return [
      "#type" => "markup",
      "#markup" => "Clients" . $results.
        "<br><a href='ccadmin/add/client'><button>Add New Client</button></a>",
    ];
  }

  public function grabCompanyClients() {
    // Uses current User to grab associated Company Groups, then grabs client nodes from Company Group
    // return $this->t(\Drupal::currentUser()->getAccountName());
    // $group = Group::load($gid);
    // $nodes = $group->getContent('plugin_id');
    $node = \Drupal::routeMatch()->getParameter('node');
    if (!$node) {}
    return $node->id;
  }
}
