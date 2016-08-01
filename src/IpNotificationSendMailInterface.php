<?php

namespace Drupal\ipnotification;

use Drupal\Core\Entity\EntityInterface;

/**
 * Interface IpNotificationSendMailInterface.
 *
 * @package Drupal\ipnotification
 */
interface IpNotificationSendMailInterface {

  /**
   * Function to check user IP when inserting a new entity.
   *
   * @return mixed
   *    returns check
   */
  public function ipCheckMailSend($current_ip, EntityInterface $element, $ids);

  /**
   * Function to check user IP while log in.
   *
   * @return mixed
   *   returns check
   */
  public function emailCheckMailSend($current_ip, $ids);

}
