<?php

namespace Drupal\kingdombride_common;

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Service implementation of KingdombrideCommonManager
 */
class KingdombrideCommonManager
{
	/**
	 * A request stack symfony instance.
	 *
	 * @var \Symfony\Component\HttpFoundation\RequestStack
	 */
	protected $requestStack;

	/**
	 * Constructs a KingdombrideCommonManager object.
	 *
	 * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
	 *   A request stack symfony instance.
	 */
	public function __construct(
		RequestStack $request_stack
	) {
		$this->requestStack = $request_stack;
	}

	/**
	 * Custom function to prepare render tags in preprocess theme such as 
	 * 'kingdombride_bef_checkboxes' and 'kingdombride_bef_radios'.
	 *
	 * @param array $variables
	 * 		Array to preprocess.
	 * @return void
	 */
	public function prepareBefTags(array &$variables)
	{
		$current_request = $this->requestStack->getCurrentRequest();
		$view_id = $variables['element']['#context']['#view_id'];
		$field_tag = $variables['element']['#name'];
		$cookie_name = $view_id . $field_tag;

		if ($current_request->cookies->has($cookie_name)) {
			$variables['tags_attributes']->setAttribute('data-view-filter-cookie', $cookie_name);
		}

		foreach ($variables['children'] as $child) {
			if (($current_request->cookies->has($cookie_name) 
				&& $current_request->cookies->get($cookie_name) == $child)
				|| $variables['element'][$child]['#checked']) {
				$variables['tags'][$child] = [
					'#type' => 'html_tag',
					'#tag' => 'div',
					'#attributes' => [
						'class' => ['label label-info'],
						'data-input-id' => $child,
					],
					'value' => [
						'#markup' => '<span class="glyphicon glyphicon-remove"></span>' . $variables['element'][$child]['#title'],
					]
				];

				$variables["element"]["#default_value"] = [$child];
				$variables['element'][$child]['#checked'] = TRUE;
			} else {
				$variables['tags'][$child] = [
					'#type' => 'html_tag',
					'#tag' => 'div',
					'#attributes' => [
						'class' => ['label label-default'],
						'data-input-id' => $child,
					],
					'#value' => $variables['element'][$child]['#title'],
				];
			}
		}

		$toto ='';
	}
}
