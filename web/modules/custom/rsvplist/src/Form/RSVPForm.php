<?php

/**
 * @file
 * Form to collect emails.
 */

namespace Drupal\rsvplist\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class RSVPForm extends FormBase {

  /**
   *{@inheritdoc}
   */
  public function getFormId()
  {
    // TODO: Implement getFormId() method.
    return 'rsvplist_email_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $node = \Drupal::routeMatch()->getParameter('node');
    if ( !is_null($node)) {
      $nid = $node->id();
    }
    else  {
      $nid = 0;
    }


    // Create form render array.
    $form['email'] = [
      '#type' => 'textfield',
      '#title' => t('Email Address'),
      '#size' => 25,
      '#description' => t('We will send updates to this email.'),
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => t('RSVP'),
    ];

    $form['nid'] = [
      '#type' => 'hidden',
      '#value' => $nid,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    $email_value = $form_state->getValue('email');
    if ( !(\Drupal::service('email.validator')->isValid($email_value)) ) {
      $form_state->setErrorByName('email',
      $this->t('%mail is not valid email', ['%mail' => $email_value]));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    try {
      // Phase 1: get variables to save.
      // Get user id.
      $uid = \Drupal::currentUser()->id();
      // Get form values.
      $nid = $form_state->getValue('nid');
      $email = $form_state->getValue('email');

      $current_time = \Drupal::time()->getRequestTime();

      // Phase 2: Query
      $query = \Drupal::database()->insert('rsvplist');

      // Specify fields that query will insert into.
      $query->fields([
        'uid',
        'nid',
        'mail',
        'created',
      ]);

      $query->values([
        $uid,
        $nid,
        $email,
        $current_time,
      ]);

      $query->execute();

      \Drupal::messenger()->addMessage( t('Thank you for your RSVP!'));
    }
    catch (\Exception $e) {
      \Drupal::messenger()->addError( t('Unable to register your RSVP due to database error.'));
    }
  }
}
