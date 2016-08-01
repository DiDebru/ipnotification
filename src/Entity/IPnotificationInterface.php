<?php

namespace Drupal\ipnotification\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining Ipnotification entities.
 */
interface IPnotificationInterface extends ConfigEntityInterface {

  /**
   * Get the mail of the ipnotification.
   *
   * @return string
   *    returns email.
   */
  public function getEmail();

  /**
   * Set the mail of the ipnotification.
   *
   * @param string $email
   *    Set mail.
   */
  public function setEmail($email);

  /**
   * Get the IP to check.
   *
   * @return string
   *    returns IP.
   */
  public function getIp();

  /**
   * Set IP to check.
   *
   * @param string $ip
   *    Set IP.
   */
  public function setIp($ip);

}
