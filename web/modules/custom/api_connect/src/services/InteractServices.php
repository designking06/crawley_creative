<?php

/**
 * @file Class file to get data from thrid party API.
 */

namespace Drupal\api_connect\services;

use Drupal;
use GuzzleHttp\Exception\RequestException;


/**
 * Class InteractServices to get data from third party API.
 * @package Drupal\api_connect\Services.
 */
class InteractServices {
  protected $api_connect_key;
  protected $api_connect_url;

  /**
   * InteractServices Constructor.
   * @param $api_connect_key
   */
  public function __construct($api_connect_key, $api_connect_url) {
    $this->api_connect_key = $api_connect_key;
    $this->api_connect_url = $api_connect_url;
  }

  public function getData($api_key, $api_url, $parameters = []) {
    $url = $api_url . '/' . implode("/", $parameters) . '?apikey=' . $api_key;
    $client = Drupal::httpClient();
    $result = [];

    try {
     $response = $client->get($url);
     $data = $response->getBody();
     $result = json_decode($data);
    }
    catch(RequestException $e) {
      Drupal::logger('api_connect')->notice($e);
    }
    return ($result);
  }

  /*
   * Usage:
   * $interactService = \Drupal::service('api_connect.InteractServices');
   * $config = \Drupal::config('api_connect.settings');
   * $api_key = $config->get('api_key');
   * $review_data = $interactService->getData($api_key, $api_url, $parameters[]);
   */
}
