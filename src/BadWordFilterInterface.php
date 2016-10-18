<?php

namespace Drupal\bad_word_filter;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining Bad word filter entities.
 */
interface BadWordFilterInterface extends ConfigEntityInterface {

  /**
   * Get entered words
   *
   * Words can be separated with comma, (e.g bad, worse)
   *
   * @param mixed $words
   *  Can be an array of words or a string.
   */
  public function getWords();

  /**
   * Get replacement string
   */
  public function getReplacement();

  /**
   * Set new filter words
   *
   * @param $words
   *  New filter words
   */
  public function setWords($words);
}
