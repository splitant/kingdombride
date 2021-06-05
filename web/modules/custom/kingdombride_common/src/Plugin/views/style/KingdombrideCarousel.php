<?php

namespace Drupal\kingdombride_common\Plugin\views\style;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\style\DefaultStyle;

/**
 * Unformatted style plugin to render rows one after another with no
 * decorations.
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "kingdombride_carousel",
 *   title = @Translation("Kingdombride Carousel"),
 *   help = @Translation("Displays rows in a carousel."),
 *   theme = "views_view_kingdombride_carousel",
 *   display_types = {"normal"}
 * )
 */
class KingdombrideCarousel extends DefaultStyle {

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['carousel_animate'] = ['default' => TRUE];
    $options['carousel_interval'] = ['default' => 5000];
    $options['carousel_wrap'] = ['default' => TRUE];
    $options['carousel_pause'] = ['default' => TRUE];
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);

    $form['carousel'] = [
      '#type' => 'details',
      '#title' => $this->t('Carousel settings'),
      '#open' => TRUE,
    ];

    $form['carousel_interval'] = [
      '#type' => 'number',
      '#title' => $this->t('Interval'),
      '#default_value' => $this->options['carousel_interval'],
      '#min' => 500,
      '#step' => 100,
      '#fieldset' => 'carousel',
    ];

    $form['carousel_animate'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Auto animate'),
      '#default_value' => $this->options['carousel_animate'],
      '#fieldset' => 'carousel',
    ];

    $form['carousel_wrap'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Loop'),
      '#default_value' => $this->options['carousel_wrap'],
      '#fieldset' => 'carousel',
    ];

    $form['carousel_pause'] = [
      '#title' => $this->t('Stop on hover'),
      '#type' => 'checkbox',
      '#default_value' => $this->options['carousel_pause'],
      '#fieldset' => 'carousel',
    ];
  }

}
