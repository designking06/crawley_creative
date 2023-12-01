<?php

namespace Drupal\client_admin\Plugin\Block;

use Drupal\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityStorageInterface;


/**
 * Provides a "Client Companies' Tweets" Block.
 *
 * @Block(
 *   id = "client_company_tweets",
 *   admin_label = @Translation("Company Clients Tweets block"),
 *   category = @Translation("Company Clients"),
 * )
 */
class TweetsBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $results = $this->grabTweets();

    return [
      "#type" => "markup",
      "#markup" => "Tweets" . $results.
        "<br><a href='ccadmin/add/client'><button>Add New Tweet</button></a>",
    ];
  }

  public function grabTweets() {
    $interact = \Drupal::service('api_connect.twitter_interact');
    return $interact->getData();
  }
}
