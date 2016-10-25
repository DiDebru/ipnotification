<?php

namespace Drupal\ipnotification\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\ipnotification\Ipnotification;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * IP notification form.
 */
class IpnotificationDelete extends ConfirmFormBase {
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
    return 'ipnotification_delete_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    if (isset($_GET['ip'])) {
      return $this->t('Are you sure you do not want to notify about the behaviour of %ip?', array('%ip' => $_GET['ip']));
    }
    elseif (isset($_GET['email'])) {
      return $this->t('Are you sure you do not want to notify about the behaviour of %email?', array('%email' => $_GET['email']));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('ipnotification.admin_page');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    if (isset($_GET['ip'])) {
      if (in_array($_GET['ip'], $this->ipNotification->findAllIps())) {
        return parent::buildForm($form, $form_state);
      }
      else {
        throw new NotFoundHttpException();
      }
    }
    if (isset($_GET['email'])) {
      if (in_array($_GET['email'], $this->ipNotification->findAllEmails())) {
        return parent::buildForm($form, $form_state);
      }
      else {
        throw new NotFoundHttpException();
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    if (isset($_GET['ip'])) {
      $this->ipNotification->deleteIp($_GET['ip']);
      $this->logger('user')->notice('Deleted %ip', array('%ip' => $_GET['ip']));
      drupal_set_message($this->t('The IP address %ip was deleted.', array('%ip' => $_GET['ip'])));
    }
    elseif (isset($_GET['email'])) {
      $this->ipNotification->deleteEmail($_GET['email']);
      $this->logger('user')->notice('Deleted %email', array('%email' => $_GET['email']));
      drupal_set_message($this->t('The email address %email was deleted.', array('%email' => $_GET['email'])));
    }
    $form_state->setRedirectUrl($this->getCancelUrl());
  }

}
