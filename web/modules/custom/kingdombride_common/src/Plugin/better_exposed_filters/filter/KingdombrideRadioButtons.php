<?php

namespace Drupal\kingdombride_common\Plugin\better_exposed_filters\filter;

use Drupal\better_exposed_filters\Plugin\better_exposed_filters\filter\RadioButtons;
use Drupal\Core\Form\FormStateInterface;

/**
 * Default widget implementation.
 *
 * @BetterExposedFiltersFilterWidget(
 *   id = "kingdombride_bef",
 *   label = @Translation("Kingdombride - Checkboxes/Radio Buttons"),
 * )
 */
class KingdombrideRadioButtons extends RadioButtons {

  /**
   * {@inheritdoc}
   */
  public function exposedFormAlter(array &$form, FormStateInterface $form_state) {
    parent::exposedFormAlter($form, $form_state);

    /** @var \Drupal\views\Plugin\views\filter\FilterPluginBase $filter */
    $filter = $this->handler;
    // Form element is designated by the element ID which is user-
    // configurable.
    $field_id = $filter->options['is_grouped'] ? $filter->options['group_info']['identifier'] : $filter->options['expose']['identifier'];


    if (!empty($form[$field_id])) {
      // Prevent Reload page save submitted value.
      $form['#attributes']['autocomplete'] = 'off';

      // Render as checkboxes if filter allows multiple selections.
      if (!empty($form[$field_id]['#multiple'])) {
        $form[$field_id]['#theme'] = 'kingdombride_bef_checkboxes';
      }
      // Else render as radio buttons.
      else {
        $form[$field_id]['#theme'] = 'kingdombride_bef_radios';
      }

      $form['#attached']['library'][] = 'kingdombride_common/kb-bef-tags';
    }
  }

}
