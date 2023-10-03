<?php

/**
 * @file
 * Form to administering settings.
 */

namespace Drupal\rsvplist\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class RSVPSettingsForm extends ConfigFormBase {

  /**
   *{@inheritdoc}
   */
  public function getFormId()
  {
    return 'rsvplist_admin_settings';
  }

  protected function getEditableConfigNames()
  {
    return [
      'rsvplist.settings',
    ];
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $types = node_type_get_names();
    $config = $this->config('rsvplist.settings');

    $form['rsvplist_types'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Content Types to enable RSVP List on.'),
      '#options' => $types,
      '#default_value' => $config->get('allowed_types'),
      '#description' => $this->t('Specify node types to enable the display of the RSVP List Form.')
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $selected_allowed_types = array_filter($form_state->getValue('rsvplist_types'));
    sort($selected_allowed_types);

    $this->config('rsvplist.settings')
      ->set('allowed_types', $selected_allowed_types)
      ->save();

    parent::submitForm($form, $form_state);
  }
}
