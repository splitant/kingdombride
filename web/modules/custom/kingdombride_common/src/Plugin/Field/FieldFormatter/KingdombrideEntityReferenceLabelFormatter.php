<?php

namespace Drupal\kingdombride_common\Plugin\Field\FieldFormatter;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceFormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Views;

/**
 * Plugin implementation of the 'kingdombride_entity_reference_label' formatter.
 *
 * @FieldFormatter(
 *   id = "kingdombride_entity_reference_label",
 *   label = @Translation("Kingdombride Label"),
 *   description = @Translation("Custom display label of the referenced entities."),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */
class KingdombrideEntityReferenceLabelFormatter extends EntityReferenceFormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'tag_classes' => '',
      'view' => TRUE,
      'view_display' => '',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements = parent::settingsForm($form, $form_state);

    $elements['tag_classes'] = [
      '#type' => 'textfield',      
      '#title' => $this->t('Tag classes'),
      '#default_value' => $this->getSetting('tag_classes'),
      '#description' => $this->t('List CSS classes of tags, separated with a space'),
    ];

    $options = Views::getViewsAsOptions(TRUE, 'enabled');

    $elements['view'] = [
      '#type' => 'select',
      '#title' => $this->t('View'),
      '#options' => $options,
      '#description' => $this->t('View to use for link with tag filter.'),
      '#empty_value' => '_none',
      '#default_value' => $this->getSetting('view'),
    ];

    $elements['view_display'] = [
      '#type' => 'textfield',      
      '#title' => $this->t('View display'),
      '#default_value' => $this->getSetting('view_display'),
      '#description' => $this->t('View display id for contextual filter (for example "page_contextual_filter").'),
      '#states' => [
        'visible' => [
          ':input[name="fields[field_tags][settings_edit_form][settings][view]"]' => [
            '!value' => '_none',
          ],
        ],
      ],
    ];

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = parent::settingsSummary();
    $summary[] = $this->getSetting('tag_classes') ? $this->t('Tag CSS classes: @classes', ['@classes' => $this->getSetting('tag_classes')]) : $this->t('No CSS class');
    $summary[] = ($this->getSetting('view') != '_none') ? $this->t('View used : @view', ['@view' => $this->getSetting('view')]) : $this->t('No view');
    $summary[] = (($this->getSetting('view') != '_none') && $this->getSetting('view_display')) ? $this->t('View display : @view_display', ['@view_display' => $this->getSetting('view_display')]) : $this->t('No view display');
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    $view = $this->getSetting('view');
    $tag_classes = $this->getSetting('tag_classes');
    $view_display = $this->getSetting('view_display');

    $view_entity = NULL;
    if (($view != '_none') && !empty($view_display)) {
      $view_entity = Views::getView($view);
      $view_entity->initDisplay();
      if ($view_entity->displayHandlers->has($view_display)) {
        $view_entity->current_display = $view_display;
      }
    }

    foreach ($this->getEntitiesToView($items, $langcode) as $delta => $entity) {
      $label = $entity->label();

      if (!empty($view_entity) && ($view_entity->displayHandlers->has($view_display))) {
        $url = $view_entity->getUrl([$entity->id()], $view_display);

        $elements[$delta] = [
          '#type' => 'link',
          '#title' => $label,
          '#url' => $url,
        ];

        if (!empty($items[$delta]->_attributes)) {
          $elements[$delta]['#options']['attributes'] = $items[$delta]->_attributes;
          // Unset field item attributes since they have been included in the
          // formatter output and shouldn't be rendered in the field template.
          unset($items[$delta]->_attributes);
        }
      }
      else {
        $elements[$delta] = [
          '#type' => 'html_tag',
          '#tag' => 'span',
          '#value' => $label,
        ];

        if (!empty($items[$delta]->_attributes)) {
          $elements[$delta] += ['#attributes' => []];
          $elements[$delta]['#attributes'] += $items[$delta]->_attributes;
          // Unset field item attributes since they have been included in the
          // formatter output and shouldn't be rendered in the field template.
          unset($items[$delta]->_attributes);
        }
      }

      if (!empty($tag_classes)) {
        if (!empty($elements[$delta]['#attributes']['class'])) {
          $elements[$delta]['#attributes']['class'] = array_merge($elements[$delta]['#attributes']['class'], explode(' ', $tag_classes));
        }
        else {
          $elements[$delta]['#attributes']['class'] = explode(' ', $tag_classes);
        }
      }
    }

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity) {
    return $entity->access('view label', NULL, TRUE);
  }

}
