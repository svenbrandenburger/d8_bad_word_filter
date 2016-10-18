<?php

namespace Drupal\bad_word_filter\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\bad_word_filter\BadWordFilterInterface;

/**
 * Defines the Bad word filter entity.
 *
 * @ConfigEntityType(
 *   id = "bad_word_filter",
 *   label = @Translation("Bad word filter"),
 *   handlers = {
 *     "list_builder" = "Drupal\bad_word_filter\BadWordFilterListBuilder",
 *     "form" = {
 *       "add" = "Drupal\bad_word_filter\Form\BadWordFilterForm",
 *       "edit" = "Drupal\bad_word_filter\Form\BadWordFilterForm",
 *       "delete" = "Drupal\bad_word_filter\Form\BadWordFilterDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\bad_word_filter\BadWordFilterHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "bad_word_filter",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/bad_word_filter/{bad_word_filter}",
 *     "add-form" = "/admin/structure/bad_word_filter/add",
 *     "edit-form" = "/admin/structure/bad_word_filter/{bad_word_filter}/edit",
 *     "delete-form" = "/admin/structure/bad_word_filter/{bad_word_filter}/delete",
 *     "collection" = "/admin/structure/bad_word_filter"
 *   }
 * )
 */
class BadWordFilter extends ConfigEntityBase implements BadWordFilterInterface {

  /**
   * The Bad word filter ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Bad word filter label.
   *
   * @var string
   */
  protected $label;

  /**
   * The Bad word filter words.
   *
   * @var string
   */
  protected $words;

  /**
   * The Bad word filter replacement.
   *
   * @var string
   */
  protected $replacement;

  /**
   * @inheritdoc
   */
  public function getWords() {
    return $this->words;
  }

  /**
   * @inheritdoc
   */
  public function getReplacement() {
    return $this->replacement;
  }

  /**
   * @inheritdoc
   */
  public function setWords($words) {
    $this->removeDuplicates($words);
    $this->words = $words;
  }

  /**
   * Remove duplicates
   *
   * @param $words
   */
  private function removeDuplicates(&$words) {
    if (!is_array($words)) {
      $words = explode(',', $words);
    }
    $words = join(', ', array_unique(array_map('trim', $words)));
  }

}
