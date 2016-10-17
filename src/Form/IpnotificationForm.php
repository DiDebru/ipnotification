<?php

namespace Drupal\ipnotification\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * IP notification form.
 */
class IpnotificationForm extends FormBase  {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ipnotification_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $default_ip = '') {
    $form['ip'] = array(
      '#title' => $this->t('IP address'),
      '#type' => 'textfield',
      '#size' => 48,
      '#maxlength' => 40,
      '#default_value' => $default_ip,
      '#description' => $this->t('Enter IP addresses comma seperated'),
    );
    $form['email'] = array(
      '#title' => $this->t('Email address'),
      '#type' => 'textfield',
      '#size' => 90,
      '#descriptiopn' => $this->t('Enter email addresses comma seperated'),
    );
    $form['actions'] = array('#type' => 'actions');
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Add'),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $ip = trim($form_state->getValue('ip'));

    drupal_set_message($this->t('The IP address %ip has been banned.', array('%ip' => $ip)));
    $form_state->setRedirect('ipnotification.admin_page');
  }

}
