<?php

/**
 * @file Class file to get data from thrid party API.
 */

namespace Drupal\api_connect\services;

use Drupal;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Noweh\TwitterApi\Client;


/**
 * Class InteractServices to get data from third party API.
 * @package Drupal\api_connect\Services.
 */
class TwitterInteractServices {
  protected $api_connect_url;
  protected $api_key;
  protected $bearer_token;
  protected $secret_key;
  protected $access_token;
  protected $access_token_s;

  /**
   * InteractServices Constructor.
   * @param $api_connect_url
   */
  public function __construct() {
    $this->config = Drupal::config('api_connect.settings');

    $settings['account_id'] = $this->config->get('account_id');

    $settings['consumer_key'] = $this->config->get('api_key');
    $settings['consumer_secret'] = $this->config->get('secret_key');

    $settings['access_token'] = $this->config->get('access_token');
    $settings['access_token_secret'] = $this->config->get('access_token_s');

    $settings['bearer_token'] = $this->config->get('bearer_token');
    $this->client = new Client($settings);
  }

  public function getData() {

    $result = [];

    try {
      $response = $this->client->timeline()->getRecentTweets($this->config->get('account_id'))->performRequest();
      $data = $response->getBody();
      $result = json_decode($data);
    }
    catch(RequestException $e) {
      Drupal::logger('api_connect')->notice($e);
    }
    catch (GuzzleException $e) {
      Drupal::logger('api_connect')->notice($e);
    }
    catch (\JsonException $e) {
      Drupal::logger('api_connect')->notice($e);
    }
    return ($result);
  }

  /*
   * Usage:
   * $interactService = \Drupal::service('api_connect.InteractServices');
   * $config = \Drupal::config('api_connect.settings');
   * $api_key = $config->get('api_key');
   * $review_data = $interactService->getData($api_key, $api_url);
   */
}
