<?php

namespace Drupal\kingdombride_common\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\text\Plugin\Field\FieldFormatter\TextDefaultFormatter;

/**
 * Plugin implementation of the 'kingdombride_text_default' formatter.
 *
 * @FieldFormatter(
 *   id = "kingdombride_text_default",
 *   label = @Translation("Kingdombride Default"),
 *   field_types = {
 *     "text",
 *     "text_long",
 *     "text_with_summary",
 *   }
 * )
 */
class KingdombrideTextDefaultFormatter extends TextDefaultFormatter {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'wrap_class' => '',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element = parent::settingsForm($form, $form_state);

    $css_classes_text_rich_options = [
      'kb-rich-text-style-1' => t('Style 1'),
      'kb-rich-text-style-2' => t('Style 2'),
    ];

    $element['wrap_class'] = [
      '#type' => 'select',
      '#title' => t('Rich text style'),
      '#default_value' => $this->getSetting('wrap_class'),
      '#options' => $css_classes_text_rich_options,
      '#description' => t('Style to apply on rich text'),
    ];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = parent::settingsSummary();

    $wrap_class_setting = $this->getSetting('wrap_class');
    if (!empty($wrap_class_setting)) {
      $summary[] = t('Style CSS class to apply : @class', ['@class' => $wrap_class_setting]);
    }
    else {
      $summary[] = t('No Style CSS selected');
    }

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = parent::viewElements($items, $langcode);

    if ($this->getSetting('wrap_class')) {
      foreach ($elements as $delta => $element) {
          $elements[$delta]['#prefix'] = '<div class="' . $this->getSetting('wrap_class') . '">';
          $elements[$delta]['#suffix'] = '</div>';
      }

      $elements['#attached']['library'][] = 'kingdombride_common/kb-text-default';
    }

    return $elements;
  }

}
