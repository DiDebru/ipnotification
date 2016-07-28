<?php

namespace Drupal\ipnotification\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\Validator\Constraints\True;

/**
 * Class IPnotificationForm.
 *
 * @package Drupal\ipnotification\Form
 */
class IPnotificationForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    /** @var \Drupal\ipnotification\Entity\IPnotification $ipnotification */
    $ipnotification = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $ipnotification->label(),
      '#description' => $this->t("Label for the Ipnotification."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $ipnotification->id(),
      '#machine_name' => [
        'exists' => '\Drupal\ipnotification\Entity\IPnotification::load',
      ],
      '#disabled' => !$ipnotification->isNew(),
    ];

    $form['ip'] = [
      '#type' => 'textfield',
      '#default_value' => $ipnotification->getIp(),
      '#description' => $this->t("Comma sperated list with IPs"),
      '#required' => TRUE,
    ];

    $form['email'] = [
      '#type' => 'textfield',
      '#default_value' => $ipnotification->id(),
      '#description' => $this->t("Comma sperated list with emails"),
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $ipnotification = $this->entity;
    $status = $ipnotification->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Ipnotification.', [
          '%label' => $ipnotification->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Ipnotification.', [
          '%label' => $ipnotification->label(),
        ]));
    }
    $form_state->setRedirectUrl($ipnotification->urlInfo('collection'));
  }

}
