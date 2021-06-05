<?php

namespace Drupal\kingdombride_common\Plugin\Layout;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Layout\LayoutDefault;

/**
 * Custom layout 'layout_kingdombride_one'.
 *
 * @Layout(
 *   id = "layout_kingdombride_one",
 *   label = @Translation("Layout Kingdombride One"),
 *   category = @Translation("Kingdombride"),
 *   template = "templates/layout-kingdombride-one",
 *   regions = {
 *     "main" = {
 *       "label" = @Translation("Main content"),
 *     }
 *   }
 * )
 */
class LayoutKingdombrideOne extends LayoutDefault {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return parent::defaultConfiguration() + [
      'layout_html' => '',
      'layout_classes' => '',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $configuration = $this->getConfiguration();

    $html_options = [
      'div' => 'Div',
      'span' => 'Span',
      'section' => 'Section',
      'article' => 'Article',
      'header' => 'Header',
      'footer' => 'Footer',
      'aside' => 'Aside',
      'figure' => 'Figure',
    ];

    $form['layout_html'] = [
      '#type' => 'select',
      '#options' => $html_options,
      '#title' => $this->t('Layout HTML'),
      '#default_value' => $configuration['layout_html'],
    ];

    $form['layout_classes'] = [
      '#type' => 'textfield',      
      '#title' => t('Layout classes'),
      '#default_value' => $configuration['layout_classes'],
      '#description' => t('List CSS classes of main layout, separated with a space'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->configuration['layout_html'] = $form_state->getValue('layout_html');
    $this->configuration['layout_classes'] = $form_state->getValue('layout_classes');
  }

  /**
   * {@inheritdoc}
   */
  public function build(array $regions) {
    $build = parent::build($regions);
    $build['#attributes']['class'] = explode(' ', $this->configuration['layout_classes']);

    return $build;
  }
}
