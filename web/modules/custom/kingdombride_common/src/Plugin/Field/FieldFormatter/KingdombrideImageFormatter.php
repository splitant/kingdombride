<?php

namespace Drupal\kingdombride_common\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\image\Plugin\Field\FieldFormatter\ImageFormatter;

/**
 * Plugin implementation of the 'kingdombride_image' formatter.
 *
 * @FieldFormatter(
 *   id = "kingdombride_image",
 *   label = @Translation("Kingdombride Image"),
 *   field_types = {
 *     "image"
 *   },
 *   quickedit = {
 *     "editor" = "image"
 *   }
 * )
 */
class KingdombrideImageFormatter extends ImageFormatter {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'image_classes' => '',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element = parent::settingsForm($form, $form_state);

    $element['image_classes'] = [
      '#type' => 'textfield',      
      '#title' => t('Image classes'),
      '#default_value' => $this->getSetting('image_classes'),
      '#description' => t('List CSS classes of image, separated with a space'),
    ];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = parent::settingsSummary();

    $image_classes_setting = $this->getSetting('image_classes');
    if (!empty($image_classes_setting)) {
      $summary[] = t('Image CSS classes: @classes', ['@classes' => $image_classes_setting]);
    }
    else {
      $summary[] = t('None CSS class');
    }

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = parent::viewElements($items, $langcode);
    
    $image_classes_setting = $this->getSetting('image_classes');
    if (!empty($image_classes_setting)) {
      foreach ($elements as &$element) {
        if (!empty($element['#item_attributes']['class'])) {
          $element['#item_attributes']['class'] = array_merge($element['#item_attributes']['class'], explode(' ', $image_classes_setting));
        }
        else {
          $element['#item_attributes']['class'] = explode(' ', $image_classes_setting);
        }
      }
    }

    return $elements;
  }
}
