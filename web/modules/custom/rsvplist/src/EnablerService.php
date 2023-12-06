<?php

/**
 * @file
 * Contains RSVP Enabler Service
 */
namespace \Drupal\rsvplist;

use Drupal\Core\Database\Connection;
use Drupal\node\Entity\Node;

class EnablerService {
  protected Connection $database_connection;

  public function __construct(Connection $connection) {
    $this->database_connection = $connection;
  }

  /**
   * Checks if a node is RSVP enabled.
   *
   * @param Node $node
   * @return bool
   */
  public function isRvspEnabled(Node &$node) {
    if ($node->isNew()) {
      return FALSE;
    }
    try {
      // Grab node ID's of nodes with enabled rsvp from db
      $select = $this->database_connection->select('rsvplist_enabled', 're');
      $select->fields('re', ['nid']);
      $select->condition('nid', $node->id());
      $results = $select->execute();

      return !(empty($results->fetchCol()));
    }
    catch (\Exception $e) {
      \Drupal::messenger()->addError(t('RSVP Enabler Error: RSVP not enabled on Node #'. $node->id()));

      return NULL;
    }
  }

  /**
   * Set Node to enable RSVP's
   * @param Node $node
   * @throw Exception
   */
  public function setEnabled(Node $node) {
    try {
      if ( !($this->isEnabled($node)) ) {
        $insert = $this->database_connection->insert('rsvplist_enabled');
        $insert->fields(['nid']);
        $insert->values([$node->id()]);
        $insert->execute();
      }
    }
    catch (\Exception $e) {
      \Drupal::messenger()->addError(
        t('RSVP Enabler Error: Unable to enable RSVP on node #' . $node->id() . '. Please try again.')
      );
    }
  }

  /**
   * @param Node $node
   * @throw Exception
   */
  public function deleteRsvpEnabledSetting(Node $node) {
    try {
      $delete = $this->database_connection->delete('rsvplist_enabled');
      $delete->condition('nid', $node->id());
      $delete->execute();
    }
    catch (\Exception $e) {
      \Drupal::messenger()->addError(
        t('RSVP Enabler Error: Unable to disable RSVP on node #' . $node->id() . '. Please try again.')
      );
    }
  }
}
