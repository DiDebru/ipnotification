<?php

namespace Drupal\ipnotification;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;

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
  public function ipCheckMailSend($current_ip, EntityInterface $element, $ids) {
    global $base_url;
    foreach ($ids as $id) {
      /** @var \Drupal\ipnotification\Entity\IPnotification $entity */
      $entity = \Drupal::entityTypeManager()->getStorage('ipnotification')->load($id);
      $ips = [];
      array_push($ips, $entity->getIp());
    }
    $url = \Drupal::service('path.current')->getPath();
    $pattern = "/" . $current_ip . "/i";
    foreach ($ips as $ip) {
      $check = preg_match($pattern, $ip);
    }
    if ($check) {
      $params['message'] = t('A new !element has been inserted from IP !current_ip !currenturl \n To !url',
                array(
                  '!element' => $element->id(),
                  '!current_ip' => check_markup($current_ip),
                  '!url' => Link::fromTextAndUrl(t('Insert'), Url::fromRoute(['absolute' => TRUE])),
                  '!currenturl' => $base_url . $url,
                ));
      $mailkey = 'ipnotification';
      $to = $entity->getEmail();
      $params['subject'] = t('New entry');
      $langcode = \Drupal::currentUser()->getPreferredLangcode();

      \Drupal::service('plugin.manager.mail')->mail('ipnotification', $mailkey, $to, $langcode, $params, NULL, TRUE);
    }
    return $check;
  }

  /**
   * Email_check_mail_send.
   *
   * @return int
   *    returns number of times ip matches.
   */
  public function emailCheckMailSend($currentip, $ids) {
    $user = \Drupal::currentUser();
    foreach ($ids as $id) {
      /** @var \Drupal\ipnotification\Entity\IPnotification $entity */
      $entity = \Drupal::entityTypeManager()->getStorage('ipnotification')->load($id);
      $ips = [];
      array_push($ips, $entity->getIp());
    }

    // Check IP from user.
    $pattern = "/" . $currentip . "/i";

    foreach ($ips as $ip) {
      $check = preg_match($pattern, $ip);
    }
    if ($check) {
      $params['message'] = t('The user !username with <b>!mail</b> has logged in.<br><br> \n To !url',
        array(
          '!username' => $user->getDisplayName(),
          '!mail' => $user->getEmail(),
          '!url' => Link::fromTextAndUrl(t('Profile'), Url::fromRoute(['absolute' => TRUE])),
        )
      );

      $mailkey = 'ipnotification';
      $to = $entity->getEmail();
      $params['subject'] = t('New login');
      $langcode = \Drupal::currentUser()->getPreferredLangcode();

      \Drupal::service('plugin.manager.mail')->mail('ipnotification', $mailkey, $to, $langcode, $params, NULL, TRUE);

    }
    return $check;
  }

}
