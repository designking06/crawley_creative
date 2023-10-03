<?php

/**
 * @file Provide admins with Reports of RSVP's.
 */

namespace Drupal\rsvplist\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;

class ReportController extends ControllerBase {

  /**
   * Gets and returns all RSVP's for all nodes.
   * Returned as associative array.
   *
   * @return array|null
   */
  protected function load() {
    try {
      $database = \Drupal::database();
      $select_query = $database->select('rsvplist', 'r');

      // Join the users table to get the entry creators username.
      $select_query->join('users_field_data', 'u', 'r.uid = u.uid');
      // Join the node table to get the Events name.
      $select_query->join('node_field_data', 'n', 'r.nid = n.nid');

      // Grab these fields.
      $select_query->addField('u', 'name', 'username');
      $select_query->addField('n', 'title', 'event');
      $select_query->addField('r', 'mail');

      $entries = $select_query->execute()->fetchAll(\PDO::FETCH_ASSOC);
      return $entries;
    }
    catch (\Exception $e) {
      \Drupal::messenger()->addStatus(
        t('Unable to access the database. Please try again.')
      );
      return null;
    }
  }

  /**
   * Creates the RSVP reports page.
   *
   * @return array
   */
  public function report() {
    $content = [];

    $content['message'] = [
      '#markup' => t('List of all Event RSVPs.'),
    ];

    $headers = [
      t('Username'),
      t('Event'),
      t('Email'),
    ];

    // Load the rows.
    $table_rows = $this->load();

    // Render array for table.
    $content['table'] = [
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $table_rows,
      '#empty' => t('No data available.'),
    ];

    // Dont store this table data in cache.
    $content['#cache']['max-age'] = 0;

    return $content;
  }
}
