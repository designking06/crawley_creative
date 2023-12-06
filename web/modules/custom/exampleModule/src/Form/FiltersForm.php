<?php

namespace Drupal\example_lineup\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class FiltersForm.
 *
 * Provides a form for filtering the table.
 */
class FiltersForm extends FormBase {

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'filters_lineup_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {

        $form['package'] = [
            '#type' => 'radios',
            '#title' => $this->t('Package'),
            '#options' => [
                'all' => 'All Packages',
                'hd' => 'HD Packages',
            ],
            '#default_value' => 'all',
        ];

        $form['channel_type'] = [
            '#type' => 'radios',
            '#title' => $this->t('Channel Types'),
            '#options' => [
                'all' => 'All Channels',
                'hd' => 'HD Channels',
            ],
            '#default_value' => 'hd',
        ];

        return $form;
    }

    /**
     * Submit function.
     *
     * @param array $form
     *   Form variable.
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *   Form state variable.
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {

    }

}
