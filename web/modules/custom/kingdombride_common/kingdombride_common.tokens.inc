<?php

/**
 * @file
 * Token integration for the kingdombride_common module.
 */

use Drupal\Core\Render\BubbleableMetadata;

/**
 * Implements hook_token_info().
 */
function kingdombride_common_token_info() {
  $info['tokens']['view']['contextual-filter'] = [
    'name' => t('Contextual filter'),
    'description' => t('The contextual filter of the view.'),
  ];

  return $info;
}

/**
 * Implements hook_tokens().
 */
function kingdombride_common_tokens($type, $tokens, array $data, array $options, BubbleableMetadata $bubbleable_metadata) {
  $replacements = [];

  if ($type == 'view' && !empty($data['view'])) {
    /** @var \Drupal\views\ViewExecutable $view */
    $view = $data['view'];

    foreach ($tokens as $name => $original) {
      switch ($name) {
        case 'contextual-filter':
          if (!empty($view->argument)) {
            
            /**
             * @var Drupal\views\Plugin\views\argument\ArgumentPluginBase $argument
             */
            $argument = reset($view->argument);
            $field_storage_definitions = \Drupal::service('entity_field.manager')->getFieldStorageDefinitions($argument->definition['entity_type']);
            
            /**
             * @var Drupal\field\Entity\FieldStorageConfig $field_storage_definition
             */
            $field_storage_definition = $field_storage_definitions[$argument->definition['field_name']];
            $target_type = $field_storage_definition->getSetting('target_type');

            $entity = \Drupal::entityTypeManager()->getStorage($target_type)->load($argument->getValue());
            $replacements[$original] = $entity->label();
          }
          
          break;
      }
    }
  }

  return $replacements;
}
