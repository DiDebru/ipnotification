<?php

namespace Drupal\ipnotification\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining Ipnotification entities.
 */
interface IPnotificationInterface extends ConfigEntityInterface {


  /**
   * @return string
   */
  public function getEmail();


  /**
   * @param string $email
   */
  public function setEmail($email);

  /**
   * @return string
   */
  public function getIp();

  /**
   * @param string $ip
   */
  public function setIp($ip);
}
