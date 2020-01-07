<?php

namespace Drupal\pets_owners\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class FormPetsOwners.
 *
 * @package Drupal\pets_owners\Form
 */
class FormPetsOwners extends FormBase {

  /**
   * @inheritDoc
   */
  public function getFormId() {
    return 'form_pets_owners';
  }

  /**
   * @inheritDoc
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#maxlength' => 100,
      '#size' => 100,
      '#required' => TRUE,
    ];
    $gender = [
      'male' => $this->t('male'),
      'female' => $this->t('female'),
      'unknown' => $this->t('unknown'),
    ];
    $form['gender'] = [
      '#type' => 'radios',
      '#title' => $this->t('Gender'),
      '#options' => $gender,
      '#default_value' => $gender['unknown'],
    ];
    $prefix = [
      'mr' => $this->t('mr'),
      'mrs' => $this->t('mrs'),
      'ms' => $this->t('ms'),
    ];
    $form['prefix'] = [
      '#type' => 'select',
      '#title' => $this->t('Prefix'),
      '#options' => $prefix,
      '#default_value' => $prefix['mr'],
    ];
    $form['age'] = [
      '#type' => 'number',
      '#title' => $this->t('Age'),
      '#min' => 1,
      '#max' => 120,
      '#required' => TRUE,
    ];
    /**
     * Only under 18 years.
     */
    $condition = [];
    for ($i = 1; $i < 18; $i++) {
      $some_e = [':input[name="age"]' => ['value' => "$i"]];
      $condition[] = $some_e;
    }
    $form['parents'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => [
          'accommodation',
        ],
      ],
      '#states' => [
        'visible' => $condition,
      ],
    ];
    $form['parents']['father'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Father`s name'),
    ];
    $form['parents']['mother'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Mother`s name'),
    ];
    $form['have_pets'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Have you some pets?'),
    ];
    $form['pets_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Names(s) of your pet(s)'),
      '#states' => [
        'invisible' => [
          'input[name="have_pets"]' => [
            'checked' => FALSE,
          ],
        ],
      ],
    ];
    $form['email'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Email'),
      '#required' => TRUE,
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];
    return $form;
  }

  /**
   * @inheritDoc
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (($form_state->getValue('age') < '1') || ($form_state->getValue('age') > '120')) {
      $form_state->setErrorByName('age', $this->t('Please enter valid age'));
    }

    if (empty(trim($form_state->getValue('name'))) || (mb_strlen($form_state->getValue('name')) > 100)) {
      $form_state->setErrorByName('name', $this->t('Please enter valid name'));
    }

    if (!$form_state->getValue('email') || !filter_var($form_state->getValue('email'), FILTER_VALIDATE_EMAIL)) {
      $form_state->setErrorByName('email', $this->t('Please enter valid email address'));
    }
  }

  /**
   * @inheritDoc
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $text = $this->t('Thank you');
    \Drupal::messenger()->addMessage($text);
  }

}
