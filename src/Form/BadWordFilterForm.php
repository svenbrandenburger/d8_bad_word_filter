<?php

namespace Drupal\bad_word_filter\Form;

use Drupal\bad_word_filter\Entity\BadWordFilter;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class BadWordFilterForm.
 *
 * @package Drupal\bad_word_filter\Form
 */
class BadWordFilterForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    /* @var $bad_word_filter BadWordFilter */
    $bad_word_filter = $this->entity;

    $form['label'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $bad_word_filter->label(),
      '#description' => $this->t("Label for the Bad word filter."),
      '#required' => TRUE,
    );

    $form['id'] = array(
      '#type' => 'machine_name',
      '#default_value' => $bad_word_filter->id(),
      '#machine_name' => array(
        'exists' => '\Drupal\bad_word_filter\Entity\BadWordFilter::load',
      ),
      '#disabled' => !$bad_word_filter->isNew(),
    );

    $form['words'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Matching terms'),
      '#maxlength' => 255,
      '#default_value' => $bad_word_filter->getWords(),
      '#description' =>
        $this->t("Define word(s) to be replaced. You can separate words with comma (e.g, bad, worse)"),
      '#required' => TRUE,
    );

    $form['replacement'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Replacement'),
      '#maxlength' => 255,
      '#default_value' => $bad_word_filter->getReplacement(),
      '#description' => $this->t("Replacement for bad words. If left blank the default replacement pattern is: ***"),
      '#required' => TRUE,
    );
    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    /* @var $bad_word_filter BadWordFilter */
    $bad_word_filter = $this->entity;
    $bad_word_filter->setWords($form_state->getValue('words'));
    $status = $bad_word_filter->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Bad word filter.', [
          '%label' => $bad_word_filter->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Bad word filter.', [
          '%label' => $bad_word_filter->label(),
        ]));
    }
    $form_state->setRedirectUrl($bad_word_filter->urlInfo('collection'));
  }

}
