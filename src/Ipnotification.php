<?php

namespace Drupal\ipnotification;

use Drupal\Core\Database\Driver\mysql\Connection;

/**
 * Class Ipnotification.
 *
 * @package Drupal\ipnotification
 */
class Ipnotification {

  /**
   * Database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Ipnotification constructor.
   *
   * @param \Drupal\Core\Database\Connection $database
   *    Database connection.
   */
  public function __construct(Connection $database) {
    $this->database = $database;
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

}
