<?php

namespace Drupal\ipnotification;

use Drupal\Core\Database\Driver\mysql\Connection;
use Drupal\Core\Mail\MailManager;
use Drupal\Core\Session\AccountProxy;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Class Ipnotification.
 *
 * @package Drupal\ipnotification
 */
class Ipnotification {
  use StringTranslationTrait;

  /**
   * Database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Request->getClientInfo().
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $request;

  /**
   * User.
   *
   * @var \Drupal\Core\Session\AccountProxy
   */
  protected $user;

  /**
   * Mail Manager.
   *
   * @var \Drupal\Core\Mail\MailManager
   */
  protected $mailManager;

  /**
   * Ipnotification constructor.
   *
   * @param \Drupal\Core\Database\Connection $database
   *    Database connection.
   */
  public function __construct(Connection $database, RequestStack $request, AccountProxy $user, MailManager $mail_manager) {
    $this->database = $database;
    $this->request = $request->getCurrentRequest();
    $this->user = $user;
    $this->mailManager = $mail_manager;
  }

  /**
   * Store IP into database.
   *
   * @param string $ip
   *    The IP to store.
   */
  public function storeIp($ip) {
    if ($ip == '') {
      return;
    }
    else {
      $this->database->insert('ipnotification_ip')
        ->fields(['ip'], [$ip])
        ->execute();
    }
  }

  /**
   * Store email into database.
   *
   * @param string $email
   *    The email to store.
   */
  public function storeEmail($email) {
    if ($email == '') {
      return;
    }
    else {
      $this->database->insert('ipnotification_email')
        ->fields(['email'], [$email])
        ->execute();
    }
  }

  /**
   * Fina all IPs stored in ipnotfication_ip table.
   *
   * @return array
   *    Array of IPs.
   */
  public function findAllIps() {
    $results = $this->database->select('ipnotification_ip', 'ipi')
      ->fields('ipi', ['ip'])
      ->execute()
      ->fetchAll();
    $ips = [];
    foreach ($results as $result) {
      $ips[] = $result->ip;
    }

    return $ips;
  }

  /**
   * Find all emails stored in ipnotification_email table.
   *
   * @return array
   *    Array of emails.
   */
  public function findAllEmails() {
    $results = $this->database->select('ipnotification_email', 'ipe')
      ->fields('ipe', ['email'])
      ->execute()
      ->fetchAll();
    $emails = [];
    foreach ($results as $result) {
      $emails[] = $result->email;
    }

    return $emails;
  }

  /**
   * Delete IP.
   *
   * @param string $ip
   *    The IP address to delete.
   */
  public function deleteIp($ip) {
    $this->database->delete('ipnotification_ip')
      ->condition('ip', $ip)
      ->execute();
  }

  /**
   * Delete email.
   *
   * @param string $email
   *    The email to delete.
   */
  public function deleteEmail($email) {
    $this->database->delete('ipnotification_email')
      ->condition('email', $email)
      ->execute();
  }

  /**
   * Send email to site admin.
   *
   * @param string $key
   *    The mail key to check on.
   * @param string $entity
   *    The current entity.
   */
  public function sendMail($key, $entity) {
    $module = 'ipnotification';
    $to = \Drupal::config('system.site')->get('mail');
    $params['title'] = $entity->label();
    $params['user'] = $this->user->getAccountName();
    $params['date'] = date('Y-m-d H:i:s');
    $params['mail'] = $this->user->getEmail();
    $params['id'] = $entity->id();

    if ($key == 'entity_create') {
      $params['message'] = $this->t('User %user created %entity %bundle %id : %title',
        array(
          '%title' => $params['title'],
          '%id' => $params['id'],
          '%user' => $params['user'],
          '%entity' => $entity->getEntityTypeId(),
          '%bundle' => $entity->bundle(),
        ));
    }
    elseif ($key == 'entity_delete') {
      $params['message'] = $this->t('User %user deleted %entity %bundle %id : %title',
        array(
          '%title' => $params['title'],
          '%id' => $params['id'],
          '%user' => $params['user'],
          '%entity' => $entity->getEntityTypeId(),
          '%bundle' => $entity->bundle(),
        ));
    }
    elseif ($key = 'entity_update') {
      $params['message'] = $this->t('User %user updated %entity %bundle %id : %title',
        array(
          '%title' => $params['title'],
          '%id' => $params['id'],
          '%user' => $params['user'],
          '%entity' => $entity->getEntityTypeId(),
          '%bundle' => $entity->bundle(),
        ));
    }
    else {
      if ($key = 'user_login') {
        $params['message'] = $this->t('User %user has logged in on %date',
          array(
            '%user' => $params['user'],
            '%date' => $params['date'],
          ));
      }
      else {
        $params['message'] = $this->t('User %user has logged out on %date',
          array(
            '%user' => $params['user'],
            '%date' => $params['date'],
          ));
      }
    }
    $langcode = $this->user->getPreferredLangcode();
    $send = TRUE;
    $result = $this->mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);

    if ($result['result'] !== TRUE) {
      drupal_set_message(t('There was a problem sending your message and it was not sent.'), 'error');
    }
    else {
      drupal_set_message(t('Your message has been sent.'));
    }
  }

  /**
   * Checks user IP.
   *
   * @return bool
   *    Returns true if we have a match.
   */
  public function checkOnIp() {
    if (in_array($this->request->getClientIp(), $this->findAllIps())) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  /**
   * Checks user mail.
   *
   * @return bool
   *    Returns true if we have a match.
   */
  public function checkOnMail() {
    if (in_array($this->user->getEmail(), $this->findAllEmails())) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

}
