<?php

namespace Drupal\ipnotification;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Class IPnotificationsendmail.
 *
 * @package Drupal\ipnotification
 */
class IPnotificationsendmail implements IPnotificationsendmailInterface {
  /**
   * Array of ips.
   *
   * @var array of ips
   */
  protected $ips;

  /**
   * User object.
   *
   * @var user object
   */
  protected $user;

  /**
   * Url object.
   *
   * @var url object
   */
  protected $url;

  /**
   * Mail object.
   *
   * @var mail object
   */
  protected $mail;

  /**
   * Array of emails.
   *
   * @var array of emails
   */
  protected $emails;

  /**
   * Constructor.
   */
  public function __construct($ips, $user, $url, $mail, $emails) {
    $this->ips = $ips;
    $this->user = $user;
    $this->url = $url;
    $this->mail = $mail;
    $this->emails = $emails;
  }

  /**
   * Ip_check_mail_send.
   *
   * @return string
   *    returns number of times ip matches.
   */
  public function ipCheckMailSend($current_ip, EntityInterface $element) {
    $url = $this->url->getPath();
    $pattern = "/" . $current_ip . "/i";
    foreach ($this->ips as $ip) {
      $check = preg_match($pattern, $ip);
    }
    if ($check) {
      $params['message'] = t('A new !element has been inserted from IP !current_ip !currenturl \n To !url',
                array(
                  '!element' => $element->id(),
                  '!current_ip' => check_markup($current_ip),
                  '!url' => Link::fromTextAndUrl(t('Insert'), Url::fromRoute(['absolute' => TRUE])),
                  '!currenturl' => $this->url,
                ));
      $mailkey = 'ipnotification';
      $to = implode(',', $this->emails);
      $params['subject'] = t('New entry');
      $langcode = $this->user->getPreferredLangcode();

      $this->mail->mail('ipnotification', $mailkey, $to, $langcode, $params, NULL, TRUE);
    }
    return $check;
  }

  /**
   * Email_check_mail_send.
   *
   * @return int
   *    returns number of times ip matches.
   */
  public function emailCheckMailSend($currentip) {
    // Check IP from user.
    $pattern = "/" . $currentip . "/i";
    foreach ($this->ips as $ip) {
      $check = preg_match($pattern, $ip);
    }
    if ($check) {
      $params['message'] = t('The user !username with <b>!mail</b> has logged in.<br><br> \n To !url',
        array(
          '!username' => $this->user->getDisplayName(),
          '!mail' => $this->user->getEmail(),
          '!url' => Link::fromTextAndUrl(t('Profile'), Url::fromRoute(['absolute' => TRUE])),
        )
      );

      $mailkey = 'ipnotification';
      $to = implode(',', $this->emails);
      $params['subject'] = t('New login');
      $langcode = $this->user->getPreferredLangcode();

      $this->mail->mail('ipnotification', $mailkey, $to, $langcode, $params, NULL, TRUE);

    }
    return $check;
  }

}
