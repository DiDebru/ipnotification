<?php

namespace Drupal\ipnotification;
use Drupal\ipnotification\Entity\IPnotification;

/**
 * Class IpNotificationSendMail.
 *
 * @package Drupal\ipnotification
 */
class IpNotificationSendMail implements IpNotificationSendMailInterface {

  /**
   * Constructor.
   */
  public function __construct() {

  }

  /**
   * Ip_check_mail_send.
   *
   * @return string
   *
   */
  public function ip_check_mail_send($current_ip, EntityTypeManager $element) {
    global $base_url;
    $ipnotification = new IPnotification();
    $ips = $ipnotification->getIp();
    $url = \Drupal::service('path.current')->getPath();
    $check = preg_match($current_ip, $ips);

    if ($check >= 1) {
      $body = t('A new !element has been inserted from IP !current_ip'. $base_url . $url, array('!element' => $element->type, '!current_ip' => check_plain($current_ip))) ."\n";
      $body .= t('To !url', array('!url' => Link::fromTextAndUrl(t('Insert'), $base_url. '/node/'. $id)));

      $mailkey = 'ipnotification';
      $to = $ipnotification->getEmail();
      $subject = t('New entry');
      $from = 'Community';
      $header = array('Content-Type' => 'text/html; charset=UTF-8; format=flowed');
      \Drupal::service('plugin.manager.mail')->mail($mailkey, $to, $subject, $body, $from, $header);
    }
    return $check;
  }

  /**
   * Email_check_mail_send
   *
   * @return int
   */
  public function email_check_mail_send() {
    global $base_url;
    global $user;
    $ipnotification = new IPnotification();
    $ips = $ipnotification->getIp();

    // Check which email domains where used by the user.
    $emaildomain = explode("@", $user->mail);
    $emaildomain = "%@" . $emaildomain[1];
    $check      = preg_match($emaildomain, $ips);
    $check      = preg_match($user->mail, $ips) + $check;

    if ($check >= 1) {
      $body = t('The user !username with <b>!mail</b> has logged in.<br><br>', array('!username' => $user->name, '!mail' => $user->mail)) ."\n";
      $body .= t('To !url', array('!url' => l(t('Profile'), $base_url. '/user/'. $user->uid)));

      $mailkey = 'ipnotification';
      $to = $ipnotification->getEmail();
      $subject = t('New login');
      $from = 'Community';
      $header = array('Content-Type' => 'text/html; charset=UTF-8; format=flowed');
      \Drupal::service('plugin.manager.mail')->mail($mailkey, $to, $subject, $body, $from, $header);
    }
    return $check;
  }

}
