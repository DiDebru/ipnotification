<?php

namespace Drupal\ipnotification;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
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
   *    returns number of times ip matches.
   */
  public function ipCheckMailSend($current_ip, EntityInterface $element) {
    global $base_url;
    $ipnotification = IPnotification::create();
    $ips = $ipnotification->getIp();
    $url = \Drupal::service('path.current')->getPath();
    $check = preg_match($current_ip, $ips);

    if ($check >= 1) {
      $body = t('A new !element has been inserted from IP !current_ip !currenturl \n To !url',
                array(
                  '!element' => $element->id(),
                  '!current_ip' => check_plain($current_ip),
                  '!url' => Link::fromTextAndUrl(t('Insert'), Url::fromUri($base_url . '/node/' . $element->id())),
                  '!currenturl' => $base_url . $url,
                ));
      $mailkey = 'ipnotification';
      $to = $ipnotification->getEmail();
      $subject = t('New entry');
      $from = 'Community';
      $header = array('Content-Type' => 'text/html; charset=UTF-8; format=flowed');
      \Drupal::service('plugin.manager.mail')->mail('ipnotification', $mailkey, $to, $subject, $body, $from, $header);
    }
    return $check;
  }

  /**
   * Email_check_mail_send.
   *
   * @return int
   *    returns number of times ip matches.
   */
  public function emailCheckMailSend() {
    global $base_url;
    $user = \Drupal::currentUser();
    $ipnotification = IPnotification::create();
    $ips = $ipnotification->getIp();

    // Check which email domains where used by the user.
    $emaildomain = explode("@", $user->getEmail());
    $emaildomain = "%@" . $emaildomain[1];
    $check      = preg_match($emaildomain, $ips);
    $check      = preg_match($user->getEmail(), $ips) + $check;

    if ($check >= 1) {
      $body = t('The user !username with <b>!mail</b> has logged in.<br><br> \n To !url',
        array(
          '!username' => $user->getDisplayName(),
          '!mail' => $user->getEmail(),
          '!url' => Link::fromTextAndUrl(t('Profile'), Url::fromUri($base_url . '/user/' . $user->id())),
        ));

      $mailkey = 'ipnotification';
      $to = $ipnotification->getEmail();
      $subject = t('New login');
      $from = 'Community';
      $header = array('Content-Type' => 'text/html; charset=UTF-8; format=flowed');
      \Drupal::service('plugin.manager.mail')->mail('ipnotification', $mailkey, $to, $subject, $body, $from, $header);

    }
    return $check;
  }

}
