<?php

namespace Drupal\ipnotification\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\ipnotification\Ipnotification;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * IP notification form.
 */
class IpnotificationForm extends FormBase {

  /**
   * Ipnotification service.
   *
   * @var \Drupal\ipnotification\Ipnotificationp
   */
  protected $ipNotification;

  /**
   * Constructs a new Ipnotification object.
   *
   * @param \Drupal\ipnotification\Ipnotification $ipnotification
   *    Ipnotification service.
   */
  public function __construct(Ipnotification $ipnotification) {
    $this->ipNotification = $ipnotification;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('ipnotification.service')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ipnotification_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $ips = $this->ipNotification->findAllIps();
    $emails = $this->ipNotification->findAllEmails();
    $rowsips = array();
    $headerips = array($this->t('IP addresses to notify.'), $this->t('Operations'));
    $rowsemails = array();
    $headeremails = array($this->t('Email addresses to notify.'), $this->t('Operations'));
    if ($ips) {
      foreach ($ips as $ip) {
        $row = array();
        $row[] = $ip;
        $links = array();
        $links['delete'] = array(
          'title' => $this->t('Delete'),
          'url' => Url::fromRoute('ipnotification.delete', ['ip' => $ip]),
        );
        $row[] = array(
          'data' => array(
            '#type' => 'operations',
            '#links' => $links,
          ),
        );
        $rowsips[] = $row;
      }
    }

    if ($emails) {
      foreach ($emails as $email) {
        $row = array();
        $row[] = $email;
        $links = array();
        $links['delete'] = array(
          'title' => $this->t('Delete'),
          'url' => Url::fromRoute('ipnotification.delete', ['email' => $email]),
        );
        $row[] = array(
          'data' => array(
            '#type' => 'operations',
            '#links' => $links,
          ),
        );
        $rowsemails[] = $row;
      }
    }
    $form['ip'] = array(
      '#title' => $this->t('IP address'),
      '#type' => 'textfield',
      '#size' => 48,
      '#maxlength' => 40,
      '#defaul_value' => '',
      '#description' => $this->t('Enter IP addresse'),
    );
    $form['email'] = array(
      '#title' => $this->t('Email address'),
      '#type' => 'textfield',
      '#size' => 48,
      '#defaul_value' => '',
      '#description' => $this->t('Enter email address'),
    );
    $form['actions'] = array('#type' => 'actions');
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Add'),
    );

    $form['ip_to_notify_table'] = array(
      '#type' => 'table',
      '#header' => $headerips,
      '#rows' => $rowsips,
      '#empty' => $this->t('No IP addresses to watch.'),
      '#weight' => 120,
    );

    $form['email_to_notify_table'] = array(
      '#type' => 'table',
      '#header' => $headeremails,
      '#rows' => $rowsemails,
      '#empty' => $this->t('No email addresses to watch.'),
      '#weight' => 120,
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
    $email = trim($form_state->getValue('email'));
    $this->ipNotification->storeIp($ip);
    $this->ipNotification->storeEmail($email);
    drupal_set_message($this->t('The IP address/es %ip and the email adress/es %email have been saved to watch', array('%ip' => $ip, '%email' => $email)));
  }

}
