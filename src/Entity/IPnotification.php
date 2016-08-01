<?php

namespace Drupal\ipnotification\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the Ipnotification entity.
 *
 * @ConfigEntityType(
 *   id = "ipnotification",
 *   label = @Translation("Ipnotification"),
 *   handlers = {
 *     "list_builder" = "Drupal\ipnotification\IPnotificationListBuilder",
 *     "form" = {
 *       "add" = "Drupal\ipnotification\Form\IPnotificationForm",
 *       "edit" = "Drupal\ipnotification\Form\IPnotificationForm",
 *       "delete" = "Drupal\ipnotification\Form\IPnotificationDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\ipnotification\IPnotificationHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "ipnotification",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "canonical" = "/admin/config/people/ipnotification/{ipnotification}",
 *     "add-form" = "/admin/config/people/ipnotification/add",
 *     "edit-form" = "/admin/config/people/ipnotification/{ipnotification}/edit",
 *     "delete-form" = "/admin/config/people/ipnotification/{ipnotification}/delete",
 *     "collection" = "/admin/config/people/ipnotification"
 *   }
 * )
 */
class IPnotification extends ConfigEntityBase implements IPnotificationInterface {

  /**
   * The Ipnotification ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Ipnotification label.
   *
   * @var string
   */
  protected $label;

  /**
   * The Ipnotification emails.
   *
   * @var string
   */
  protected $email;

  /**
   * The Ipnotification ips.
   *
   * @var string
   */
  protected $ip;

  /**
   * Get the email of an entity of type ipnotification.
   *
   * @return string
   *    returns the email
   */
  public function getEmail() {
    return $this->email;
  }

  /**
   * Set the email.
   *
   * @param string $email
   *    Mail.
   */
  public function setEmail($email) {
    $this->email = $email;
  }

  /**
   * Get the IP of an entity of type ipnotification.
   *
   * @return string
   *    returns the IP
   */
  public function getIp() {
    return $this->ip;
  }

  /**
   * Set the IP.
   *
   * @param string $ip
   *    The IP.
   */
  public function setIp($ip) {
    $this->ip = $ip;
  }

}
