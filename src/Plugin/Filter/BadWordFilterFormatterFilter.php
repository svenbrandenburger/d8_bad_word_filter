<?php

namespace Drupal\bad_word_filter\Plugin\Filter;

use Drupal\Core\Form\FormStateInterface;
use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;

/**
 * @Filter(
 *   id = "bad_word_filter",
 *   title = @Translation("Bad word filter"),
 *   description = @Translation("Provides the ability to filter bad words"),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_MARKUP_LANGUAGE,
 * )
 */
class BadWordFilterFormatterFilter extends FilterBase {

  /**
   * @inheritdoc
   */
  public function process($text, $langcode) {
    $this->replaceWords($text);
    return new FilterProcessResult($text);
  }

  /**
   * @inheritdoc
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $storage = $this->getStorage();
    $configs = $storage->loadMultiple();

    foreach ($configs as $key => $config) {
      $configs[$key] = $config->label();
    }

    $form['active_configs'] = [
      '#type' => 'select',
      '#title' => $this->t('Enabled Configuration(s)'),
      '#options' => $configs,
      '#default_value' => !empty($this->settings['active_configs']) ? $this->settings['active_configs'] : [],
      '#description' => $this->t('Select your configuration(s)'),
      '#multiple' => TRUE,
    ];
    return $form;
  }

  /**
   * Helper to get entity storage
   *
   * @return \Drupal\Core\Entity\EntityStorageInterface
   */
  protected function getStorage() {
    return \Drupal::entityTypeManager()->getStorage($this->plugin_id);
  }


  /**
   * Replacement logic for bad word filter
   *
   * @param $text
   *  Text to check for bad words and replace them
   */
  protected function replaceWords(&$text) {
    $active_configs = !empty($this->settings['active_configs']) ? $this->settings['active_configs'] : [];
    if (empty($active_configs)) {
      return;
    }
    $storage = $this->getStorage();
    foreach ($active_configs as $id) {
      /* @var $config \Drupal\bad_word_filter\Entity\BadWordFilter */
      $config = $storage->load($id);

      $filter_words = explode(',', $config->getWords());
      $filter_words = array_map('trim', $filter_words);
      $pattern = '/\b(' . implode('|', $filter_words) . ')\b/i';
      $text = preg_replace($pattern, $config->getReplacement(), $text);
    }
  }
}