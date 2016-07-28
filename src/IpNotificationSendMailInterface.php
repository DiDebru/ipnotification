<?php

namespace Drupal\ipnotification;
use Drupal\Core\Entity\Entity;
use Drupal\Core\Entity\EntityInterface;

/**
 * Interface IpNotificationSendMailInterface.
 *
 * @package Drupal\ipnotification
 */
interface IpNotificationSendMailInterface {

  /**
   * @param $current_ip
   * @param \Drupal\ipnotification\EntityInterface $element
   * @return mixed
   */
  public function ip_check_mail_send($current_ip, EntityInterface $element);

  /**
   * @return mixed
   */
  public function email_check_mail_send();
}
