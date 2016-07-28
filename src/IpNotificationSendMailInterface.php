<?php

namespace Drupal\ipnotification;

/**
 * Interface IpNotificationSendMailInterface.
 *
 * @package Drupal\ipnotification
 */
interface IpNotificationSendMailInterface {

  /**
   * @param $current_ip
   * @param \Drupal\ipnotification\EntityTypeManager $element
   * @return mixed
   */
  public function ip_check_mail_send($current_ip, EntityTypeManager $element);

  /**
   * @return mixed
   */
  public function email_check_mail_send();
}
