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
 *     "email" = "email",
 *     "ip" = "ip"
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

}
